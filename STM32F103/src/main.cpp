/***********************************************************************************************/
/*
/// Project: 
/// Description:
/// Author: Phan Tuan Nhat
*/
/***********************************************************************************************/

#include <Arduino.h>
#include <string.h>
#include <TinyGPS++.h>
#include <HX711_ADC.h>
#include <ArduinoJson.h>

// Limited switch
#define OPEN_LIMIT_PIN      PB4     
#define CLOSED_LIMIT_PIN    PB3

// US-115 Ultrasonic Sensor
#define US015_TRIG_PIN      PB5     
#define US015_ECHO_PIN      PB6

// HX711 ADC Converter for load cell
// #define HX711_CLK_PIN       PB8
// #define HX711_DOUT_PIN      PB9
#define HX711_CLK_PIN       PA6
#define HX711_DOUT_PIN      PA5


// L298N module controls motor to open-closed lid of trash can
#define LOCK_PIN            PA8     
#define INT1_PIN            PB14
#define INT2_PIN            PB15

#define UV_LIGHT_PIN        PB13



/*====================================== OBJECTS ========================================*/ 
TinyGPSPlus gps;
HX711_ADC LoadCell(HX711_DOUT_PIN, HX711_CLK_PIN);

HardwareSerial serialWithESP(PA3, PA2);
HardwareSerial serialWithGPS(PB11, PB10);

DynamicJsonDocument doc(1024);


/*====================================== VARIABLES ======================================*/ 
String incomingString;

enum State{OPEN, CLOSE, UNDEFINED};
State lidState;

volatile bool isClosing = false;
volatile bool isOpening = false;

volatile unsigned long timeLidHasClosed;
volatile unsigned long timeLidHasOpened;
volatile unsigned long timeOutClosed;

volatile bool updateData;
unsigned long timeTurnOnUVLight;
bool destroyBacteria = true;


// GPS data
double GPS_Latitude = 16.078592;
double GPS_Longitude = 108.212085;
String GPS_Date = "Not available";
String GPS_Time = "Not available"; 
unsigned long cycleToGetData = 0;    
// Amount garbage inside the bin (unit : %)
int garbageCapacity;
// Garbage weight inside the bin (unit : kg)
float weight;




/*=================================== Function Prototypes ===================================*/
void openLimit_ISR();
void closedLimit_ISR();

void openProcedure();
void closedProcedure();

int getDistance();


/*========================================= SET UP =========================================*/
void setup() {

  Serial.begin(115200);         // USB to TTL
  serialWithESP.begin(9600);    
  serialWithGPS.begin(9600);        
  
  pinMode(US015_TRIG_PIN,OUTPUT);   
  pinMode(US015_ECHO_PIN,INPUT);

  pinMode(OPEN_LIMIT_PIN, INPUT);
  attachInterrupt(digitalPinToInterrupt(OPEN_LIMIT_PIN),openLimit_ISR, FALLING);
  pinMode(CLOSED_LIMIT_PIN, INPUT);
  attachInterrupt(digitalPinToInterrupt(CLOSED_LIMIT_PIN),closedLimit_ISR, FALLING);

  pinMode(UV_LIGHT_PIN, OUTPUT);
  digitalWrite(UV_LIGHT_PIN, LOW);

  pinMode(LOCK_PIN, OUTPUT);
  pinMode(INT1_PIN, OUTPUT);
  pinMode(INT2_PIN, OUTPUT);
  digitalWrite(INT1_PIN, LOW);
  digitalWrite(INT2_PIN, LOW);


  /* Initialize for HX711 module */
  LoadCell.begin();
  float calibrationValue = 104.41;    // Set the calibration value in the sketch
  unsigned long stabilizingtime = 2000; // preciscion right after power-up can be improved by adding a few seconds of stabilizing time
  bool _tare = true; //set this to false if you don't want tare to be performed in the next step
  LoadCell.start(stabilizingtime, _tare);
  if (LoadCell.getTareTimeoutFlag()) {
    Serial.println("Timeout, check MCU > HX711 wiring and pin designations");
    while (1);
  }
  else {
    LoadCell.setCalFactor(calibrationValue); // set calibration value (float)
    Serial.println("Startup is complete");
  }


  /* Get lid state */
  if(digitalRead(OPEN_LIMIT_PIN) == HIGH && digitalRead(CLOSED_LIMIT_PIN) == LOW){
    lidState = State::CLOSE;
    Serial.println("The lid of trash is closed");
  }else if(digitalRead(OPEN_LIMIT_PIN) == HIGH && digitalRead(CLOSED_LIMIT_PIN) == LOW){
    lidState = State::OPEN;
    Serial.println("The lid of trash is open");
    closedProcedure();
  }else{
    lidState = State::UNDEFINED;
    Serial.println("The lid of trash is undefined");
    closedProcedure();
  }

  /* Handshake between ESP32 and BluePill, make sure both devices are ready */
  serialWithESP.print("BlueIsReady\r");
  while (true)
  { 
    char bufferData;
    if(serialWithESP.available() > 0)
    {
      bufferData =  serialWithESP.read();
      incomingString += bufferData;
      if(bufferData == '\r'){
        if(incomingString.indexOf("ESP32IsReady") >= 0){
          incomingString = "";
          break;
        }
      }
    }
  }

  Serial.println("------------------------------------------------------------");
  Serial.println(">> Blue Pill is ready.");
  Serial.println("------------------------------------------------------------");
}



/*===================================== MAIN PROGRAM ======================================*/
void loop() 
{
  /* Get data coming from ESP32 board, check the message to control or send data back to ESP32 */
  char paddingCharacter;
  while(serialWithESP.available() > 0)
  {
    paddingCharacter = serialWithESP.read();
    incomingString += paddingCharacter;
    if(paddingCharacter == '\r')
    {
      if((incomingString.indexOf("open") >= 0) && lidState == State::CLOSE)
      {
        openProcedure();
      }
      else if(incomingString.indexOf("updateData") >= 0)
      {
        doc["updateData"]       = true;
        doc["weight"]           = weight;
        doc["garbageCapacity"]  = garbageCapacity;
        doc["gps"][0]           = GPS_Latitude;
        doc["gps"][1]           = GPS_Longitude;
        doc["gps"][2]           = GPS_Date; 
        doc["gps"][3]           = GPS_Time;
  
        serializeJson(doc, serialWithESP);
        serialWithESP.print("\r");
      }

      Serial.print("|>> Comand from ESP32 Master: [");
      Serial.print(incomingString);
      Serial.print("]\n");

      incomingString = "";   // Remove to get new string 
    }
  }

  /*  */
  if(State::CLOSE  == lidState)
  {
    // Checking if Lid of trash can has closed for 2 seconds
    if(millis() - timeLidHasClosed > 2000 && updateData == true)
    {
      // get Amount of trash inside 
      int amountOfTrash;
      for(int i = 0; i < 5; i++){
        amountOfTrash = getDistance();
        delay(500);
      }
      Serial.print("Garbage in bin: ");
      Serial.println(amountOfTrash);   // Distance is usually 47 (cm)
      amountOfTrash -= 4;  // We need to subtract 4cm to keep the distance from the sensor to the garbage
      garbageCapacity = 100 - map(amountOfTrash, 0, 43, 0, 100);

      // Prepare to send data to ESP32
      doc["updateData"]       = true;
      doc["weight"]           = weight;
      doc["garbageCapacity"]  = garbageCapacity;
      doc["gps"][0]           = GPS_Latitude;
      doc["gps"][1]           = GPS_Longitude;
      doc["gps"][2]           = GPS_Date; 
      doc["gps"][3]           = GPS_Time;

      serializeJson(doc, serialWithESP);
      serialWithESP.print("\r");

      Serial.println(">> Update data: ");
      serializeJson(doc, Serial);
      Serial.println(" ");

      updateData = false;
      destroyBacteria = false;   // After the data are updated, enable to destroy bacteria
    }

    // Using UV lights to destroy bacteria
    if(destroyBacteria == false)
    {
      digitalWrite(UV_LIGHT_PIN, HIGH);
      timeTurnOnUVLight = millis();
      serialWithESP.print("turnOnUVLight\r");
      destroyBacteria = true;
    }
    if(digitalRead(UV_LIGHT_PIN) == HIGH){
      if(millis() - timeTurnOnUVLight > 1000*60*10){
        digitalWrite(UV_LIGHT_PIN, LOW);
        serialWithESP.print("turnOffUVLight\r");
      }
    }
  }
  else if(State::OPEN  == lidState) 
  {
    //Check, if no one is standing in front of the trash can during 10 seconds
    int distance;
    int timeRemainingToClose;

    distance = getDistance();
    Serial.print("Distance: ");
    Serial.println(distance);

    if(distance <= 70){
      timeOutClosed = millis();
    }

    timeRemainingToClose = 6 - ((millis() - timeOutClosed) / 1000);

    Serial.print("Lid of trash can going to close after [ ");
    Serial.print(timeRemainingToClose);
    Serial.println(" ] seconds");

    if(timeRemainingToClose == 0){
      closedProcedure();
      Serial.println("==> Close Procedure");     
    }

    delay(1000);
  }


  /* Get GPS (Global Position System) */
  if( (millis() - cycleToGetData) > 5000)
  {
    while (serialWithGPS.available() > 0)
    {
      if (gps.encode(serialWithGPS.read()))
      {
        // GPS location
        if(gps.location.isValid()){
          GPS_Latitude = gps.location.lat();
          GPS_Longitude = gps.location.lng();
        }else{
        }
        // GPS date
        if (gps.date.isValid())
        {
          GPS_Date = String(gps.date.month()) + "/" + String(gps.date.day()) + "/" + String(gps.date.year());
        }
        else{
        }
        // GPS time
        if (gps.time.isValid())
        {
          GPS_Time = String(gps.time.hour()) + ":" + String(gps.time.minute()) + ":" + String(gps.time.second());
        }
        else{
        }
      }
    }
    cycleToGetData = millis();
  }

  /* Get weight of trash */
  for(int numRead = 0; numRead < 50; numRead++){
    if(LoadCell.update()){
      weight = LoadCell.getData() / 1000.0 ;
    }
    delay(5);
  }

}


/*===================================== FUNCTIONS =====================================*/

/*
/// Interrupt Service Routine for Open Limit Switch
*/
void openLimit_ISR(){
  // Stop motor
  for(int i = 0; i < 20000; i++);
  if(digitalRead(OPEN_LIMIT_PIN) == LOW && isClosing == false){
    digitalWrite(INT1_PIN, LOW);
    digitalWrite(INT2_PIN, LOW);
    lidState = State::OPEN;
    isOpening=  false;
    timeLidHasOpened = millis();
    timeOutClosed = millis();

    serialWithESP.print("LidIsOpened\r");
    Serial.println("--| Open Limit!");
  }
  
}


/*
/// Interrupt Service Routine for Closed Limit Switch
*/
void closedLimit_ISR(){
  // Stop motor
  for(int i = 0; i < 20000; i++);
  if(digitalRead(CLOSED_LIMIT_PIN) == LOW && isOpening == false){
    digitalWrite(INT1_PIN, LOW);
    digitalWrite(INT2_PIN, LOW);
    lidState = State::CLOSE; 
    isClosing = false;
    timeLidHasClosed = millis();
    
    // The solution momentarily avoid noise for limit switch
    if(digitalRead(UV_LIGHT_PIN) == LOW){
      updateData = true; 
    }

    serialWithESP.print("LidIsClosed\r");
    Serial.println("|-- CLosed Limit!");
  }

}


/*
///
*/
void openProcedure(){
  isOpening = true;
  if(digitalRead(UV_LIGHT_PIN) == HIGH){
    digitalWrite(UV_LIGHT_PIN, LOW);
    delay(1000);
  }
  digitalWrite(LOCK_PIN, HIGH);
  digitalWrite(INT1_PIN, HIGH);
  digitalWrite(INT2_PIN, LOW);
  delay(2000);
  digitalWrite(LOCK_PIN, LOW);
}


/*
/// 
*/
void closedProcedure(){
  isClosing = true;
  digitalWrite(INT1_PIN, LOW);
  digitalWrite(INT2_PIN, HIGH);
  serialWithESP.print("closedProcedure\r"); 
}


/*
/// This function will get distance from ultrasonic sensor
*/
int getDistance() {
  unsigned long duration;
  int distance;
  
  digitalWrite(US015_TRIG_PIN,0);   
  delayMicroseconds(2);
  digitalWrite(US015_TRIG_PIN,1);   
  delayMicroseconds(5);   
  digitalWrite(US015_TRIG_PIN,0); 

  duration = pulseIn(US015_ECHO_PIN, HIGH);
  distance = int(duration/2/29.412);

  return distance;  //Distance unit: (cm)
}


