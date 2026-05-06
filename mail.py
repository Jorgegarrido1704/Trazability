import smtplib
import datetime
dateNOw = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
# Import the email modules we'll need
from email.message import EmailMessage

# Open the plain text file whose name is in textfile for reading.
texto="""
MPS FILES UPDATED AT """ + dateNOw
msg = EmailMessage()
msg.set_content(texto)
destinatarios_validos = ['jgarrido@mx.bergstrominc.com', 'cgustafson@bergstrominc.com','ttuckley@bergstrominc.com','jgamboa@mx.bergstrominc.com','apreciado@mx.bergstrominc.com']    

# me == the sender's email address
# you == the recipient's email address
msg['Subject'] = 'MPS FILES UPDATED'
msg['From'] = 'jgarrido@mx.bergstrominc.com'
msg['To'] =  ", ".join(destinatarios_validos)

# Send the message via our own SMTP server.
s = smtplib.SMTP('mail.bergstrominc.com', 25)
s.send_message(msg)
s.quit()
