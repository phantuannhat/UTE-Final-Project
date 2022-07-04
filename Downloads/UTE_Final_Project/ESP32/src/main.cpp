/***********************************************************************************************/
/*
/// Project: 
/// Description:
/// Author: Phan Tuan Nhat
*/
/***********************************************************************************************/

#include <Arduino.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <EEPROM.h>
#include <Adafruit_NeoPixel.h>
#include <SoftwareSerial.h>
#include <ArduinoJson.h>


#define EEPROM_SIZE       12      // Store ID of the user tag

#define SS_PIN            5       // MFRC522 RFID Reader
#define RST_PIN           2

#define BUZZER_PIN        32

#define PIXEL_PIN         33      // WS2812 RGB LED
#define NUM_OF_PIXEL      9

#define BUTTON_PIN        34

#define RX_PIN            26      //  Connect to Control Board
#define TX_PIN            27


/*====================================== OBJECTS ========================================*/ 
MFRC522 mfrc522(SS_PIN, RST_PIN);
MFRC522::MIFARE_Key userKey;
MFRC522::MIFARE_Key staffKey;
MFRC522::MIFARE_Key defaultKey;

Adafruit_NeoPixel pixels(NUM_OF_PIXEL, PIXEL_PIN, NEO_GRB + NEO_KHZ800);

SoftwareSerial controlSerial;

DynamicJsonDocument doc(1024);


/*====================================== VARIABLES ======================================*/ 
// Information network and server
const char* ssid = "LED";
const char* password = "tuannhat";
const char* serverName = "http://smarttrash.pw/connect/data.php"; 


// Token code used to authenticate with server
const String token = "tuannhat";
// GPS data
double GPS_Latitude;
double GPS_Longitude;
String GPS_Date;
String GPS_Time;    
// Amount garbage inside the bin (unit : %)
int garbageCapacity;
// Garbage weight inside the bin (unit : kg)
float weight;
// Enable to update data on server
bool updateData = false;

// RFID
enum Tag{ USER, STAFF, UNKNOWN};
Tag rfidTag;

byte userUID[4];
byte staffUID[4];
byte newUID[4];     
byte userAuthenticationKey[6] = {0x44, 0x61, 0x6e, 0x61, 0x6e, 0x67};    //String: "Danang"
byte staffAuthenticationKey[6] = {0x01, 0x02, 0x03, 0x04, 0x05, 0x06};   //String: "123456"


volatile bool updateUserTag = false;
volatile unsigned long myTime;

boolean fullGarbage;

bool destroyBacteria = false;
unsigned long timeTurnOnUVLight = 0;

unsigned long timeNetworkStatus = 0;

// Data buffer is got from BluePill board through serial port
String incomingString;

// Trash can state
enum State{OPEN, CLOSE, UNDEFINED};
volatile State lidState = CLOSE;




/*=================================== Function Prototypes ===================================*/
//Interrupt Service Routine
void IRAM_ATTR updateUserTag_ISR(); 

boolean getTag(byte *);
boolean isUser();
boolean isStaff();
boolean encodeUserTag();
void printHexArray(byte *, byte);

void postDataToServer(String, double, double, float, int);
void postDataToServer(String, double, double, float, int, byte *);
void postRequest(String);

void openProcedure();
void closedProcedure();

void turnOnBuzzerSignal(int, int);
void rainbowCycle(uint8_t);
uint32_t Wheel(byte);
void theaterChase(uint32_t, uint8_t);
void effectNetworkStatus(wl_status_t);
void effectIsReady(uint8_t);


/*========================================= SET UP =========================================*/
void setup() {
  //Serial2.begin(9600);
  //while (!Serial2);
  Serial.begin(9600);     
  while (!Serial);

  controlSerial.begin(9600, SWSERIAL_8N1, RX_PIN, TX_PIN, false, 256);
	controlSerial.enableIntTx(false);

  pinMode(BUTTON_PIN, INPUT_PULLUP);
  attachInterrupt(digitalPinToInterrupt(BUTTON_PIN), updateUserTag_ISR, FALLING);

  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(BUZZER_PIN, LOW);    // Turn off buzzer
  
  pixels.begin();            //Initialize Neopixel strip object
  pixels.clear();
  pixels.show();


  SPI.begin();              // Init SPI bus
  mfrc522.PCD_Init();       // Init MFRC522 card

  for(byte i = 0; i < 6; i++){
    userKey.keyByte[i] = userAuthenticationKey[i];
  }

  for(byte i = 0; i < 6; i++){
    staffKey.keyByte[i] = staffAuthenticationKey[i];
  }

  for(byte i = 0; i < 6; i++){
    defaultKey.keyByte[i] = 0xFF;
  }

  //Get user UID from EEPROM
  EEPROM.begin(EEPROM_SIZE);
  int eepromAddress = 0;
  for(int i = 0; i < 4; i++){
    userUID[i] = EEPROM.read(eepromAddress);
    eepromAddress += 1;
  }
  Serial.print(" User ID: ");
  printHexArray(userUID, 4);
  

  /* Conect to Network */
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    effectNetworkStatus(WiFi.status());
    Serial.println("Connecting to WiFi..");
    delay(500);
  }
  Serial.println("Connected to the WiFi network");

  //Blinking led to indicate ESP32 connected to Network
  for(uint8_t x = 0; x < 4; x++){
    pixels.setPixelColor(4, pixels.Color(0,0,255));
    pixels.show();
    delay(200);
    pixels.setPixelColor(4, pixels.Color(0,0,0));
    pixels.show();
    delay(200);
  } 
  // Print local IP address and start web server
  Serial.println("");
  Serial.println("WiFi connected.");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP()); //show ip address when connected on serial monitor.
  delay(500);

  /* Handshake between ESP32 and BluePill, make sure both devices are ready*/
  while (true)
  { 
    char bufferData;
    if(controlSerial.available() > 0)
    {
      bufferData = controlSerial.read();
      incomingString += bufferData;
      if(bufferData == '\r'){
        if(incomingString.indexOf("BlueIsReady") >= 0){
          incomingString = "";
          break;
        }
      }
    }
  }
  controlSerial.print("ESP32IsReady\r");
  effectIsReady(100);

  Serial.println("------------------------------------------------------------");
  Serial.println(">> ESP32 is ready.");
  Serial.println("------------------------------------------------------------");
}


/*===================================== MAIN PROGRAM ======================================*/
void loop() {
  
  /* Connect to Internet, update data on server */
  if(WiFi.status()== WL_CONNECTED) {

    if(updateData == true) {
      if(Tag::STAFF == rfidTag) {
        postDataToServer(token, GPS_Latitude, GPS_Longitude, weight, garbageCapacity, staffUID);
      }
      else {
        postDataToServer(token, GPS_Latitude, GPS_Longitude, weight, garbageCapacity);
      }
      updateData = false;
    }
  }
  
  /* If user's tag is lost, the Smart trash need to add a new user tag */
  if(updateUserTag == true ){
    Serial.println("\n -------Update User Tag-------");
    turnOnBuzzerSignal(1, 200);
    Serial.println("Step : Please put staff's tag onto RFID reader!");
    while((millis() - myTime) < 5000)
    { 
      if(getTag(newUID))
      {
        if(isStaff())
        {
          Serial.println(">> Staff tag is authenticated!");
          delay(500);
          Serial.println("Step 2: Please put white tag on RFID reader to create user's tag!");
          turnOnBuzzerSignal(1, 100);
          myTime = millis();
          while((millis() - myTime) < 5000)
          {    
            if(getTag(newUID))
            {
              if(encodeUserTag() == true)
              {
                // Store new UID into EEPROM 
                int eepromAddr = 0;
                for(int i = 0; i < 4; i++){
                  userUID[i] = newUID[i];
                  EEPROM.write(eepromAddr, userUID[i]);   
                  eepromAddr += 1;
                }
                EEPROM.commit();
                Serial.println(">> User's UID is saved in EEPROM");

                turnOnBuzzerSignal(2, 100);
                break; 
              }
            }
          }
          break; 
        }   
      }
    }
    Serial.println("--------End of Update--------");
    updateUserTag = false;
  }

  /* Read RFID tags */
  if(getTag(newUID)) 
  {
    if(isUser()) 
    {
      Serial.println(" [USER] Please! Give me the garbage");
      openProcedure();
      rfidTag = Tag::USER;
      controlSerial.print("open\r");
    }else if (isStaff())
    {
      Serial.println(" [STAFF] Please! Take garbage out");
      openProcedure();
      rfidTag = Tag::STAFF;
      for(int i = 0; i < 4; i++){
        staffUID[i] = newUID[i];
      }
      controlSerial.print("open\r");
    }else
    {
      rfidTag = Tag::UNKNOWN;
      Serial.println(" [UNKNOW] Tag is invalid!");
    }
  }
  
  /* Communicate with BluePil through serial port */
  char paddingCharacter;
  while(controlSerial.available() > 0)
  {
    paddingCharacter = controlSerial.read();
    incomingString += paddingCharacter;

    if(paddingCharacter == '\r')
    {
      if(incomingString.indexOf("LidIsOpened") >= 0)
      {
        lidState = State::OPEN;
      }
      else if(incomingString.indexOf("LidIsClosed") >= 0)
      {
        lidState = State::CLOSE;
      }
      else if(incomingString.indexOf("turnOnUVLight") >= 0)
      {
        destroyBacteria = true;
        timeTurnOnUVLight = millis();
      }
      else if(incomingString.indexOf("turnOffUVLight") >= 0)
      {
        destroyBacteria = false;
      }
      else if(incomingString.indexOf("closedProcedure") >= 0)
      {
        closedProcedure();
      }
      else if(incomingString.indexOf("updateData") >= 0)
      {
        deserializeJson(doc, incomingString);
        
        updateData         = doc["updateData"];
        weight             = doc["weight"];
        garbageCapacity    = doc["garbageCapacity"];
        GPS_Latitude       = doc["gps"][0];
        GPS_Longitude      = doc["gps"][1];
        const char *date   = doc["gps"][2]; 
        const char *time   = doc["gps"][3];
        GPS_Date = date;
        GPS_Time = time;
      }

      Serial.println("|>> Comand from STM32 BluePill: [");
      Serial.println("[ " + incomingString + " ]");
      
      incomingString = "";   // Remove to get new string
    }
  }
  

  /* Turn on LED to indicate the operation status*/
  //Network status
  if((millis() - timeNetworkStatus) > 10000)
  {
    effectNetworkStatus(WiFi.status());

    if(WiFi.status() == WL_CONNECTED)
    {
      Serial.println("Wi-Fi connected!");
    }
    else
    {
      Serial.println("Wi-Fi disconnected!");
      WiFi.disconnect();
      WiFi.reconnect();
    }
    timeNetworkStatus = millis();
  }
  //Detroying bacteria
  if((millis() - timeTurnOnUVLight) > 10000 && destroyBacteria == true){
    theaterChase(pixels.Color(0,255,255), 100); //   GRB
    timeTurnOnUVLight = millis();
  }

  

}


/*===================================== FUNCTIONS =====================================*/

/*
/// To enable the user tag update function for trash can 
*/
void IRAM_ATTR updateUserTag_ISR() {
  updateUserTag = true;
  myTime = millis();
}


/*
/// 
*/
boolean getTag(byte *buffer) {
  // Reset the loop if no new card present on the sensor/reader.
  if ( ! mfrc522.PICC_IsNewCardPresent())
    return false;
  
  // Select one of the cards
  if ( ! mfrc522.PICC_ReadCardSerial())
    return false;

  for(byte i = 0; i < mfrc522.uid.size; i++){
    buffer[i] = mfrc522.uid.uidByte[i];
  }  
  return true;
}

/*
///  Check if tag has been read is user
*/
boolean isUser() {
  for(int i = 0; i < 4; i++){
    if(mfrc522.uid.uidByte[i] != userUID[i]){
      return false;
    }
  }

  int trailerBlock = 7;
  MFRC522::StatusCode status;
  status = (MFRC522::StatusCode) mfrc522.PCD_Authenticate(MFRC522::PICC_CMD_MF_AUTH_KEY_A,
                                                          trailerBlock,
                                                          &userKey,
                                                          &(mfrc522.uid));
  if(status != MFRC522::STATUS_OK){
    mfrc522.PICC_HaltA();         //Halt PICC
    mfrc522.PCD_StopCrypto1();    //Stop encryption on PCD
    return false;
  }

  mfrc522.PICC_HaltA();         //Halt PICC
  mfrc522.PCD_StopCrypto1();    //Stop encryption on PCD
  return true;
}


/*
/// Check if tag has been read is staff
*/
boolean isStaff() {
  int trailerBlock = 7;
  MFRC522::StatusCode status;
  
  //Serial.println(F("Authenticating staff using key A..."));
  status = (MFRC522::StatusCode) mfrc522.PCD_Authenticate(MFRC522::PICC_CMD_MF_AUTH_KEY_A,
                                                          trailerBlock,
                                                          &staffKey,
                                                          &(mfrc522.uid));
  if (status != MFRC522::STATUS_OK) {
    mfrc522.PICC_HaltA();         //Halt PICC
    mfrc522.PCD_StopCrypto1();    //Stop encryption on PCD
    return false;
  }
  
  mfrc522.PICC_HaltA();         //Halt PICC
  mfrc522.PCD_StopCrypto1();    //Stop encryption on PCD
  return true;
}


/*
///
*/
boolean encodeUserTag() {

  MFRC522::StatusCode status;
  byte trailerBlock = 7;
  byte blockAddr = 7;
  byte accessBit[4] = {0xFF, 0x07, 0x80, 0x69};
  
  byte dataBlock[16] = {};        // This array use to write data onto sector trailer 
  
  for(int i = 0; i < 16; i++){
    if(i >= 0 && i < 6){          // 6 bytes from 0 to 5 for "Key A"
      dataBlock[i] = userKey.keyByte[i];
    }else if(i >= 6 && i < 10){    // 4 bytes from 6 to 9 for "Access Bits"
      dataBlock[i] = accessBit[i - 6];
    }else{                        // 6 bytes from 10 to 15 for "Key B"
      dataBlock[i] = defaultKey.keyByte[i - 10];
    }
  }

  // Authenticate using key B
  Serial.println(F("Authenticating again using key B..."));
  status = (MFRC522::StatusCode) mfrc522.PCD_Authenticate(MFRC522::PICC_CMD_MF_AUTH_KEY_B,
                                                          trailerBlock,
                                                          &defaultKey,
                                                          &(mfrc522.uid));
  if (status != MFRC522::STATUS_OK) {
    mfrc522.PICC_HaltA();         //Halt PICC
    mfrc522.PCD_StopCrypto1();    //Stop encryption on PCD
    return false;
  }

  // Write data to the block
  Serial.print(F("Writing data into block ")); Serial.print(blockAddr);
  Serial.println(F(" ..."));
  printHexArray(dataBlock, 16); Serial.println();
  status = (MFRC522::StatusCode) mfrc522.MIFARE_Write(blockAddr, dataBlock, 16);
  
  Serial.println();

  Serial.println(F("Authenticating using key A..."));
  status = (MFRC522::StatusCode) mfrc522.PCD_Authenticate(MFRC522::PICC_CMD_MF_AUTH_KEY_A,
                                                          trailerBlock,
                                                          &userKey,
                                                          &(mfrc522.uid));
  if (status != MFRC522::STATUS_OK) {
    mfrc522.PICC_HaltA();         //Halt PICC
    mfrc522.PCD_StopCrypto1();    //Stop encryption on PCD
    return false;
  }
  
  mfrc522.PICC_HaltA();         //Halt PICC
  mfrc522.PCD_StopCrypto1();    //Stop encryption on PCD
  return true;
}


/*
///
*/
void printHexArray(byte *buffer, byte bufferSize) {
    for (byte i = 0; i < bufferSize; i++){
        Serial.print(buffer[i] < 0x10 ? " 0" : " ");
        Serial.print(buffer[i], HEX);
    }
}

/*
/// 
*/
void postDataToServer(String token, double latitude, double longitude, float weight, int garbageCapacity)
{
  String _location = String(latitude, 6) + "," + String(longitude, 6);
  String _weight = String(weight);
  String _capacity = String(garbageCapacity);

  // Prepare your HTTP POST request data
  String httpRequestData=  ""; 
  httpRequestData += "token="       + token;
  httpRequestData += "&location="   + _location;
  httpRequestData += "&weight="     + _weight;
  httpRequestData += "&garbagepercent=" + _capacity  + "";

  postRequest(httpRequestData);
}


/*
/// 
*/
void postDataToServer(String token, double latitude, double longitude, float weight, int garbageCapacity, byte *staffUID){
  
  String _location = String(latitude, 6) + "," + String(longitude, 6);
  String _weight = String(weight);
  String _capacity = String(garbageCapacity);
  String _uid;

  // Convert byte array {0xXX, 0xXX, 0xXX, 0xXX} to string "XX:XX:XX:XX"
  int leng = sizeof(staffUID) / sizeof(byte);
  for(int i = 0; i < leng; i++){
    _uid += staffUID[i] < 0x10 ? "0" : "";
    _uid += String(staffUID[i], HEX);
    _uid += i < (leng - 1) ? ":" : "";
  }
  
  // Prepare your HTTP POST request data
  String httpRequestData=  ""; 
  httpRequestData += "token="       + token;
  httpRequestData += "&location="   + _location;
  httpRequestData += "&weight="     + _weight;
  httpRequestData += "&garbagepercent=" + _capacity;
  httpRequestData += "&uid="        + _uid + "";

  postRequest(httpRequestData);
}


/*
///
*/
void postRequest(String httpRequestData){
  WiFiClient client;
  HTTPClient http;

  http.begin(client, serverName);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  
  Serial.print("httpRequestData: ");
  Serial.println(httpRequestData);
  
  // Send HTTP POST request

   int httpResponseCode = http.POST(httpRequestData);
 
   String payload = http.getString(); 
      
  if (httpResponseCode > 0) {
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);
    Serial.println(payload);  
  }
  else {
    Serial.print("Error code: ");
    Serial.println(httpResponseCode);
  }
  
  http.end();
}


/*
///
*/
void openProcedure(){
  turnOnBuzzerSignal(2, 100);
  destroyBacteria = false;
  // NeoPixels effect for closed procedure
  int halfOfStrip = pixels.numPixels() / 2;
  if((pixels.numPixels() % 2) == 1)
  {
    for(int j = 0; j <= halfOfStrip; j++)
    {
      pixels.setPixelColor(halfOfStrip - j, pixels.Color(250,0,0));
      pixels.setPixelColor(halfOfStrip + j, pixels.Color(250,0,0));
      pixels.show();
      delay(150);
      pixels.setPixelColor(halfOfStrip - j, pixels.Color(0,0,0));
      pixels.setPixelColor(halfOfStrip + j, pixels.Color(0,0,0));
      pixels.show();

    }
  }
}


/*
/// 
*/
void closedProcedure(){
  turnOnBuzzerSignal(3, 100);
  
  // NeoPixels effect for closed procedure
  int halfOfStrip = pixels.numPixels() / 2;
  if((pixels.numPixels() % 2) == 1)
  {
    for(int j = 0; j <= halfOfStrip; j++)
    {
      pixels.setPixelColor(j, pixels.Color(250,0,0));
      pixels.setPixelColor((halfOfStrip * 2) - j, pixels.Color(250,0,0));
      pixels.show();
      delay(150);
      pixels.setPixelColor(j, pixels.Color(0,0,0));
      pixels.setPixelColor((halfOfStrip * 2) - j, pixels.Color(0,0,0));
      pixels.show();
    }
  }
}


/*
/// Create sound of the Buzzer
*/
void turnOnBuzzerSignal(int numberOfPlay, int delayTime){
  for(int i = 0; i < numberOfPlay; i++){
    digitalWrite(BUZZER_PIN, HIGH);
    delay(delayTime);
    digitalWrite(BUZZER_PIN, LOW);
    delay(delayTime);
  }
}

/*
// Slightly different, this makes the rainbow equally distributed throughout
*/
void rainbowCycle(uint8_t wait) {
  uint16_t i, j;

  for(j=0; j<256*5; j++) { // 5 cycles of all colors on wheel
    for(i=0; i< pixels.numPixels(); i++) {
      pixels.setPixelColor(i, Wheel(((i * 256 / pixels.numPixels()) + j) & 255));
    }
    pixels.show();
    delay(wait);
  }

  for (int x = 0; x < pixels.numPixels(); x++)
  {
    pixels.setPixelColor(x, pixels.Color(0,0,0));
  }
  pixels.show();
  
}

/*
//Theatre-style crawling lights.
*/
void theaterChase(uint32_t c, uint8_t wait) {
  for (int j=0; j<10; j++) {  //do 10 cycles of chasing
    for (int q=0; q < 3; q++) {
      for (uint16_t i=0; i < pixels.numPixels(); i=i+3) {
        pixels.setPixelColor(i+q, c);    //turn every third pixel on
      }
      pixels.show();

      delay(wait);

      for (uint16_t i=0; i < pixels.numPixels(); i=i+3) {
        pixels.setPixelColor(i+q, 0);        //turn every third pixel off
      }
    }
  }
  
  for (int x = 0; x < pixels.numPixels(); x++)
  {
    pixels.setPixelColor(x, pixels.Color(0,0,0));
  }
  pixels.show();
}

/*
// Input a value 0 to 255 to get a color value.
// The colours are a transition r - g - b - back to r.
*/
uint32_t Wheel(byte WheelPos) {
  WheelPos = 255 - WheelPos;
  if(WheelPos < 85) {
    return pixels.Color(255 - WheelPos * 3, 0, WheelPos * 3);
  }
  if(WheelPos < 170) {
    WheelPos -= 85;
    return pixels.Color(0, WheelPos * 3, 255 - WheelPos * 3);
  }
  WheelPos -= 170;
  return pixels.Color(WheelPos * 3, 255 - WheelPos * 3, 0);
}


/*
///
*/
void effectNetworkStatus(wl_status_t status)
{
  if(status == WL_CONNECTED)
  {
    for(uint16_t i = 0; i < 255; i++){
      pixels.setPixelColor(4, pixels.Color(0,0, i));
      pixels.show();
      delay(10);    
    }
    for(uint16_t i = 0; i <= 255; i++){
      pixels.setPixelColor(4, pixels.Color(0, 0, 255 - i));
      pixels.show();
      delay(5);    
    }
  }else
  {
    for(uint16_t i = 0; i < 255; i++){
      pixels.setPixelColor(4, pixels.Color(i, i, 0));
      pixels.show();
      delay(10);    
    }
    for(uint16_t i = 0; i <= 255; i++){
      pixels.setPixelColor(4, pixels.Color(255 - i, 255 - i, 0));
      pixels.show();
      delay(5);    
    }
  }
}


void effectIsReady(uint8_t wait){
  uint8_t x;
  int numPixels = pixels.numPixels();
  for(x = 0; x < numPixels; x++){
    pixels.setPixelColor(x, pixels.Color(0, 0, 255));
    pixels.show();
    delay(wait);
    pixels.setPixelColor(x, pixels.Color(0, 0, 0));
    pixels.show();
  }
  for(x = 1; x <= numPixels; x++){
    pixels.setPixelColor(numPixels - x, pixels.Color(0, 0, 255));
    pixels.show();
    delay(wait);
    pixels.setPixelColor(numPixels - x, pixels.Color(0, 0, 0));
    pixels.show();
  }
}
