#include "_ffmc16.h"
#include "extern.h"
#include "monitor.h"
void configureIO()
{
	/*IO Pins*/
	IO_PDR1.bit.P10 = 0;
	IO_DDR1.byte = 0x1E;//D10 as input
	IO_PDR3.bit.P30 = 0;
	IO_DDR3.bit.D30 = 1;//sensor trigger
	
	IO_PDR2.bit.P25 = 0;
	IO_DDR2.bit.D25 = 0;//
	
	IO_PDR2.bit.P24 = 0;
	IO_DDR2.bit.D24 = 0;//external interrupt	
	
	IO_DDR2.bit.D27 = 0;
	
	IO_DDR4.bit.D42 = 1;//transmitting
	IO_DDR4.bit.D40 = 0;
	//IO_DDR5.bit.D50 = 0;
	/*Interrupt Priority*/
	IO_ICR04.byte = 0x02;// free run
	IO_ICR06.byte = 0x01;// free run input capture0/External
	/*Transmit UART*/
	IO_ICR13.byte = 0x00;
	IO_CDCR1.byte = 0x80;
	IO_SMR1.byte = 0x1;
	IO_SCR1.byte = 0x15;
	IO_SSR1.bit.TIE=1;
	
	/*Receive UART
	IO_SCR1.byte = 0x17;
	
	//IO_SSR1.byte = 0x00;
	
	/*Free Run Timer*/
	IO_TCCS.bit.STOP = 1;
	IO_ICS01.byte = 0x11;
	IO_TCCS.byte = 0x69;//0.5us
	IO_TCCS.bit.STOP = 1;
	/*Input Capture 0*/
	IO_ICS01.byte = 0x12;//detects falling edge
	/*External Interrupt*/
	IO_ENIR.bit.EN4 = 0;
	IO_ELVR.word = 0x0200;//detects rising edge
	IO_EIRR.bit.ER4 = 0;//external request
	
}