
#ifndef tasksched
#define tasksched

typedef struct task
{
	int period;
	int elapsedTime;
	void (*TickFct)(void);
} task;
extern task tasks[2];
extern int tasksPeriodGCD;
extern int periodToggle;
extern int periodSequence;
extern int processingRdyTasks;
extern int waterLevel; 
extern int freeRunOF; 
void TimerSet(int milliseconds);
void TimerON();
void TimerOFF();
void loadTask();
/*tasks*/
void TickFct_Toggle();
void TickFct_Sequence();
void sendUART(unsigned char data);
unsigned char readUART();
void readDistance();

#endif
