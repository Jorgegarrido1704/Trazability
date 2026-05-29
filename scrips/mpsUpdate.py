import pymysql
from pymysql import cursors,connect 
import datetime
from datetime import date


# Connect to the database
connection = pymysql.connect(host='localhost',
    user='root',
    password='',
    db='toi')

cursor = connection.cursor()
cursor.execute("SELECT * FROM lecturas")
result = cursor.fetchall()
