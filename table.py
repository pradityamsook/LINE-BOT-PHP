from django.shortcuts import render
from django.http import HttpResponse

from .models import Greeting

import requests
import psycopg2
# Create your views here.

def index(request):
    try:
        Conn = psycopg2.connect(dbname = "dat0leg3greg82" ,user = "bbpwzpntvbwgky" ,host = "ec2-54-243-252-91.compute-1.amazonaws.com" ,password = "839a6f43817bbc7d60acc475b6aee9dd88b6d761eee57431dc8422e8433b0152")
        Cur = Conn.cursor()

        sql_command = """
        CREATE TABLE employees02 (
        staff_number INTEGER PRIMARY KEY,
        fname VARCHAR(20),
        lname VARCHAR(30),
        gender CHAR(1),
        joining DATE,
        birth_date DATE)"""

        Cur.execute(sql_command)
        Conn.commit()
        to  = "Complete"
    except psycopg2.Error as e:
        print(str(e))
        to = "Error please check your syntax ,not connect"

    return HttpResponse(to)
#def index(request):
    #b = requests.get('http://httpbin.org/status/418')
    #print(b.text)
    #return HttpResponse('<pre>' + b.text + '</pre>')
    #return render(request, 'index.html')


def db(request):

    greeting = Greeting()
    greeting.save()

    greetings = Greeting.objects.all()

    return render(request, 'db.html', {'greetings': greetings})
