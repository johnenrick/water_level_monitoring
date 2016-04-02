#include "_ffmc16.h"
#include "extern.h"
#include "monitor.h"
#include "tasksched.h"

int isReadingDistance;
void delay()
{
	int ctr;
	for(ctr = 0; ctr < 30000; ctr++);
}
void main()
{

	int command = 0, ctr;
	unsigned char Rx_data=0;
	isReadingDistance = 0;
	__set_il(7);
	__EI();
	configureIO();
	while(1)
	{
		IO_PDR1.bit.P11 = 1;
		IO_PDR1.bit.P12 = 1;
		IO_PDR1.bit.P13 = 1;
		IO_PDR1.bit.P14 = 1;
		
		command = readUART();
		IO_PDR1.bit.P12 = 0;
		if(command == 65){//read water level command
			
			readDistance();
			command = 0;
			IO_PDR1.bit.P14 = 0;
		}
		for(ctr = 0; ctr < 1000; ctr++);
		//force exit
		if(IO_PDR2.bit.P25 == 0){
			IO_PDR1.bit.P11 = 1;
			IO_PDR1.bit.P12 = 1;
			IO_PDR1.bit.P13 = 0;
			IO_PDR1.bit.P14 = 1; 
			return;
		}
		
	}
	
}
/* ISR */
__interrupt void capture_int(void)
{
	IO_TCCS.bit.STOP = 1;//stops free run timer
	IO_ENIR.bit.EN4 = 0;//disable external interrupt
	sendUART((IO_IPCP0)>> 8);
	sendUART((IO_IPCP0) & 0x00FF);
	IO_ICS01.bit.ICP0 = 0; // clears interrupt flag
	isReadingDistance = 0;
	
}
__interrupt void freerun_int(void)//timer overflow
{
	IO_TCCS.bit.IVF = 0;// clears interrupt flag
    IO_TCCS.bit.STOP = 1;// stops the timer
    IO_ENIR.bit.EN4 = 0;//disable external interrupt
    isReadingDistance = 0;
    //force exit
    if(IO_PDR2.bit.P25 == 0){
			IO_PDR1.bit.P11 = 1;
			IO_PDR1.bit.P12 = 0;
			IO_PDR1.bit.P13 = 1;
			IO_PDR1.bit.P14 = 0;
			return;
		}
}
__interrupt void ext_int(void)
{
	IO_EIRR.bit.ER4 = 0;//clear external interrupt request
	IO_ENIR.bit.EN4 = 0;//disable external interrupt
	IO_TCCS.bit.STOP = 0;
}

#pragma section INTVECT,locate=0xfffc00
#pragma intvect _start 0x8 0x0
#pragma intvect freerun_int 19
#pragma intvect ext_int 24
#pragma intvect capture_int 23