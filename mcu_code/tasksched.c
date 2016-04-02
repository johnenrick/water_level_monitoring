#include "_ffmc16.h"
#include "extern.h"
#include "monitor.h"
#include "tasksched.h"

//defining tasks
void sendUART(unsigned char data){
	while(!IO_SSR1.bit.TDRE);
	IO_SCR1.byte = 0x15;
	IO_SIDR1.byte = data;
	
}
unsigned char readUART(){
	unsigned char Rx_data;
	IO_SCR1.byte = 0x17;
	while(!IO_SSR1.bit.RDRF){
		IO_PDR1.bit.P11 = 0;
		IO_PDR1.bit.P13 = !IO_PDR1.bit.P13;
		//force exit
		if(IO_PDR2.bit.P25 == 0){
			IO_PDR1.bit.P11 = 0;
			IO_PDR1.bit.P12 = 1;
			IO_PDR1.bit.P13 = 0;
			IO_PDR1.bit.P14 = 1;
			return 0;
		}
	}
	Rx_data = IO_SIDR1.byte;
	return Rx_data;
}
void readDistance(){
	int currentDigit, ctr;
	if(isReadingDistance == 0){
		while(IO_TCCS.bit.STOP == 0);
		//Trigger sensor
		IO_TCCS.bit.STOP = 1;//ensure timer not counting and required when writing to TCDT
		IO_TCDT = 0;
		IO_ENIR.bit.EN4 = 1;//enable external interrupt
		IO_PDR3.bit.P30 = 0;
		for(currentDigit = 0; currentDigit <= 10000; currentDigit++);
		IO_PDR3.bit.P30 = 1;
		for(currentDigit = 0; currentDigit <= 10; currentDigit++)
		IO_PDR3.bit.P30 = 0;
		isReadingDistance = 1;
	}
}
