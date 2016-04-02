//	Listener.cpp - Sample application for CSerial
//
//	Copyright (C) 1999-2003 Ramon de Klein (Ramon.de.Klein@ict.nl)
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

#define STRICT
#include <tchar.h>
#include <windows.h>
#include <stdio.h>
#include <string.h>
#include "Serial.h"


enum { EOF_Char = 27 };
char* readMCU(char *comd);
int ShowError (LONG lError, LPCTSTR lptszMessage)
{
	// Generate a message text
	TCHAR tszMessage[256];
	wsprintf(tszMessage,_T("%s\n(error code %d)"), lptszMessage, lError);

	// Display message-box and return with an error-code
	::MessageBox(0,tszMessage,_T("Listener"), MB_ICONSTOP|MB_OK);
	return 1;
}
CSerial serial;
LONG    lLastError;
int __cdecl _tmain (int /*argc*/, char** /*argv*/)
{
    
	lLastError = ERROR_SUCCESS;
    // Attempt to open the serial port (COM1)
    lLastError = serial.Open(_T("COM3"),0,0,false);
	if (lLastError != ERROR_SUCCESS)
		return ::ShowError(serial.GetLastError(), _T("Unable to open COM-port"));
    // Setup the serial port (9600,8N1, which is the default setting)
    lLastError = serial.Setup(CSerial::EBaud9600,CSerial::EData8,CSerial::EParNone,CSerial::EStop1);
	if (lLastError != ERROR_SUCCESS)
		return ::ShowError(serial.GetLastError(), _T("Unable to set COM-port setting"));
    // Register only for the receive event
    lLastError = serial.SetMask(CSerial::EEventBreak |
								CSerial::EEventCTS   |
								CSerial::EEventDSR   |
								CSerial::EEventError |
								CSerial::EEventRing  |
								CSerial::EEventRLSD  |
								CSerial::EEventRecv);
	if (lLastError != ERROR_SUCCESS)
		return ::ShowError(serial.GetLastError(), _T("Unable to set COM-port event mask"));
	
	// Use 'non-blocking' reads, because we don't know how many bytes
	// will be received. This is normally the most convenient mode
	// (and also the default mode for reading data).
    lLastError = serial.SetupReadTimeouts(CSerial::EReadTimeoutNonblocking);
	if (lLastError != ERROR_SUCCESS)
		return ::ShowError(serial.GetLastError(), _T("Unable to set COM-port read timeout."));

	/*******/
    // Keep reading data, until an EOF (CTRL-Z) has been received
	char *szBuffer;
	
	while (1){
		readMCU("A");
		Sleep(1000);
	}
	
    // Close the port again
	
    serial.Close();
    return 0;
}

char * readMCU(char * comd){
	bool fContinue = true;
	int charToSend = 0;
	do{
		DWORD dwBytesRead = 0;
		char szBuffer[101];
		DWORD distance = 0;
		
		do{
			serial.Write(comd);
			lLastError = serial.Read(szBuffer, sizeof(szBuffer)-1, &dwBytesRead);
			if (lLastError != ERROR_SUCCESS){
				::ShowError(serial.GetLastError(), _T("Unable to read from COM-port."));
				return 0;
			}
			if (dwBytesRead > 0){
				szBuffer[dwBytesRead] = '\0';
				distance = (szBuffer[0] << 8) + (szBuffer[1] & 0x00FF);
				printf("\n%i %i  = %d us = %d cm \n", szBuffer[0], szBuffer[1], distance, ((distance) / (DWORD)116.00));
				if (strchr(szBuffer, EOF_Char)){
					fContinue = false;
				}
				return szBuffer;
			}
			else{
				printf(".");
			}
		} while (dwBytesRead == sizeof(szBuffer)-1);
		Sleep(100);
	} while (fContinue);

}
