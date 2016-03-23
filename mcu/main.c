#include "_ffmc16.h"
#include "extern.h"
#include "monitor.h"
#include "tasksched.h"

task tasks[2];
int tasksPeriodGCD;
int periodToggle;
int periodSequence;
int processingRdyTasks;
int waterLevel = -1; 
int freeRunOF = 0; 
void delay()
{
	int ctr;
	for(ctr = 0; ctr < 30000; ctr++);
}
void main()
{

	int ctr = 0, i = 0, tasksNum = 3, looper =6, currentDigit, waterLevel = 888;
	unsigned char Rx_data=0;
	
	__set_il(7);
	__EI();
	configureIO();
	//loadTask();
	//TimerSet(1000);
	//TimerON();
	while(1)
	{
		readDistance();
		if(IO_PDR2.bit.P27 == 0){
			
			IO_PDR1.bit.P14 = 0;
			for(ctr = 0; ctr < 30000;ctr++);
		}else{
			IO_PDR1.bit.P14 = 1;
		}
		if(IO_PDR2.bit.P25 == 0){
			return;
		}
		/*Rx_data = readUART();
		if(Rx_data)
		{
			IO_PDR1.bit.P11 = !IO_PDR1.bit.P11;
			if(Rx_data == 'A')
			{
				IO_PDR1.bit.P12 = 0;
				IO_PDR1.bit.P13 = 1;
			}else if(Rx_data == 'B'){
				IO_PDR1.bit.P12 = 1;
				IO_PDR1.bit.P13 = 0;
			}else{
				IO_PDR1.bit.P12 = 1;
				IO_PDR1.bit.P13 = 1;
			}
		}
		
		IO_PDR1.bit.P14= !IO_PDR1.bit.P14;
		Rx_data = 0;
		/*for (i = 0; i < tasksNum; i++)
		{
			
			if (tasks[i].elapsedTime >= tasks[i].period)
			{
				tasks[i].TickFct();
				tasks[i].elapsedTime = 0;
			}
			tasks[i].elapsedTime += tasksPeriodGCD;
		}*/
		
	}
	
}
/* ISR */
__interrupt void capture_int(void)
{
	
	IO_TCCS.bit.STOP = 1;//stops free run timer
	IO_ENIR.bit.EN4 = 0;//disable external interrupt
	sendUART((IO_IPCP0)>> 8);
	sendUART((IO_IPCP0) & 0x00FF);
	IO_PDR1.bit.P11 = 0;
	IO_ICS01.bit.ICP0 = 0; // clears interrupt flag
	
}
__interrupt void freerun_int(void)//timer overflow
{
	IO_TCCS.bit.IVF = 0;// clears interrupt flag
    IO_TCCS.bit.STOP = 1;// stops the timer
    IO_ENIR.bit.EN4 = 0;//disable external interrupt
    IO_PDR1.bit.P12 = 0;//indicator
}

__interrupt void ext_int(void)
{
	IO_EIRR.bit.ER4 = 0;//clear external interrupt request
	IO_ENIR.bit.EN4 = 0;//disable external interrupt
	IO_TCCS.bit.STOP = 0;
	IO_PDR1.bit.P13 = 0;//indicator
}

#pragma section INTVECT,locate=0xfffc00
#pragma intvect _start 0x8 0x0
#pragma intvect freerun_int 19
#pragma intvect ext_int 24
#pragma intvect capture_int 23