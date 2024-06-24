import smtplib
import ssl
from email.message import EmailMessage

def send_email_alert(alert_message):
    email_sender = 'asmaaalaabaker@gmail.com'
    email_password = 'vxun rsbs pann fovz'
    email_receiver = 'abdolmoneaalaabaker@gmail.com'
    
    subject = 'Network Security Alert: Attack Detected'
    body = f"""
    Dear Network Administrator,

    {alert_message}

    Regards,

    VIGILNET Network Intrusion Detection System
    """
    
    em = EmailMessage()
    em['From'] = email_sender
    em['To'] = email_receiver
    em['Subject'] = subject
    em.set_content(body)
    
    context = ssl.create_default_context()
    
    with smtplib.SMTP_SSL('smtp.gmail.com', 465, context=context) as smtp:
        smtp.login(email_sender, email_password)
        smtp.sendmail(email_sender, email_receiver, em.as_string())
