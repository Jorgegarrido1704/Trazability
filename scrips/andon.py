from machine import Pin
import time

# (0: Blanco, 1: Azul, 2: Verde, 3: Amarillo, 4: Rojo)
LED_PINS = [13, 12, 14, 27, 26] 

leds = [Pin(p, Pin.OUT) for p in LED_PINS]


for led in leds:
    led.value(0)
leds[2].value(1) 

def set_leds(index, value):
    leds[index].value(value)

op_req_cal_btn   = Pin(15, Pin.IN, Pin.PULL_UP) 
op_req_mant_btn  = Pin(2,  Pin.IN, Pin.PULL_UP) 
tec_cal_help_btn = Pin(4,  Pin.IN, Pin.PULL_UP) 
tec_mant_help_btn = Pin(16, Pin.IN, Pin.PULL_UP) 

alerta_calidad = False
alerta_mantenimiento = False

while True:
   
    if not op_req_cal_btn.value():
        alerta_calidad = True
        time.sleep(0.2) 
        
   
    if not tec_cal_help_btn.value():
        alerta_calidad = False
        time.sleep(0.2) 

    if not op_req_mant_btn.value():
        alerta_mantenimiento = True
        time.sleep(0.2) 
        
   
    if not tec_mant_help_btn.value():
        alerta_mantenimiento = False
        time.sleep(0.2) 


    if alerta_calidad:
        set_leds(1, 1) # Azul ON
    else:
        set_leds(1, 0) # Azul OFF

   
    if alerta_mantenimiento:
        set_leds(3, 1) # Amarillo ON
    else:
        set_leds(3, 0) # Amarillo OFF

   
    if not alerta_calidad and not alerta_mantenimiento:
        set_leds(2, 1) # Verde ON
        set_leds(4, 0) # Rojo OFF
    else:
        set_leds(2, 0) # Verde OFF
        set_leds(4, 1) # Rojo ON

    time.sleep(0.05) # Pequeña pausa para estabilidad del procesador