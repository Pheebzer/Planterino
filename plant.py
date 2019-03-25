import sys
import time
from RPi import GPIO
import spidev
spi = spidev.SpiDev()
spi.open(0, 0)
spi.max_speed_hz = 250000

GPIO.setmode(GPIO.BCM)
GPIO.setup(2, GPIO.OUT)

def poll_sensor(channel):
   """Poll MCP3002 ADC
   Args:
        channel (int):  ADC channel 0 or 1
   Returns:
        int: 10 bit value relating voltage 0 to 1023
        """
   if channel:
      cbyte = 0b11000000
   else:
      cbyte = 0b10000000

        # Send (Start bit=1, cbyte=sgl/diff & odd/sign & MSBF = 0)
   r = spi.xfer2([1, cbyte, 0])

        # 10 bit value from returned bytes (bits 13-22):
        # XXXXXXXX, XXXX####, ######XX
   part = ((r[1] & 31) << 6) + (r[2] >> 2)

   x = 100-((part - 490)*100/(1023-490))
   x = int(x)
   if x >= 100:
      x = 100
      return x
   else:
      return x

def Pump_on():
   GPIO.output(2, GPIO.LOW)
   time.sleep(0.5)
   GPIO.output(2, GPIO.HIGH)
   print("Letting the water settle...")
   time.sleep(10.0)
   return

def main():
   print("Auto-water mode started!")
   try:
       while True:
           time.sleep(5.0)
           channel = 0
           moisture = poll_sensor(channel)
           print("Moisture is "+str(moisture)+"%")
           if moisture < 50:
              print("Moisture below 50, squirting...")
              while moisture < 50:
                 Pump_on()
                 moisture = poll_sensor(channel)
                 print("Moisture is "+str(moisture)+"%")
           else:
              pass

   except KeyboardInterrupt:
      GPIO.cleanup()
      sys.exit()
if __name__ == "__main__":
   main()
