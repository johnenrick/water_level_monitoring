#include "_ffmc16.h"
#include "extern.h"
#include "monitor.h"
#include "tasksched.h"
void TimerSet(int milliseconds)
{
	IO_TMR[0] = milliseconds * 62;
}
void TimerON()
{
	IO_TMCSR0.bit.CNTE = 1;
}
void TimerOFF()
{
	IO_TMCSR0.bit.CNTE = 0;
}
void loadTask()
{
	int i=0;
	//initializing globals
	processingRdyTasks = 0;
	tasksPeriodGCD = 200;
	waterLevel = -1;
	freeRunOF = 0;
	
	tasks[i].period = 200;
	tasks[i].elapsedTime = tasks[i].period;
	tasks[i].TickFct = &readDistance;
	++i;
	tasks[i].period = 200;
	tasks[i].elapsedTime = tasks[i].period;
	//tasks[i].TickFct = &sendUART;
	
}
//defining tasks
void sendUART(unsigned char data){
	while(!IO_SSR1.bit.TDRE);
	IO_SCR1.byte = 0x15;
	IO_SIDR1.byte = data;
	
}
unsigned char readUART(){
	unsigned char Rx_data;
	IO_SCR1.byte = 0x17;
	while(!IO_SSR1.bit.RDRF);
		Rx_data = IO_SIDR1.byte;
	return Rx_data;
}
void readDistance(){
	int currentDigit, ctr;
	while(IO_TCCS.bit.STOP == 0){
	
	}
	//indicator
	IO_PDR1.bit.P13 = 1;
	IO_PDR1.bit.P12 = 1;
	IO_PDR1.bit.P11 = 1;
	//Trigger sensor
	IO_TCCS.bit.STOP = 1;//ensure timer not counting and required when writing to TCDT
	IO_TCDT = 0;
	IO_ENIR.bit.EN4 = 1;//enable external interrupt
	IO_PDR3.bit.P30 = 0;
	for(currentDigit = 0; currentDigit <= 32000; currentDigit++);
	IO_PDR3.bit.P30 = 1;
	for(currentDigit = 0; currentDigit <= 10; currentDigit++)
	IO_PDR3.bit.P30 = 0;
}
