﻿<div id="gpcs\_model">
#策略模型
用户定义模型集控的**周期模型**和**动作模型**。
![](/Project/GPCS/Resources/images/h_strategy.png)
**周期模型**可以定义一个**开始日期**和**结束日期**，同时定义在这个时间段内的**循环模式**和**节假日模式**。
**　　日循环模式**表示每天执行
**　　自定义模式**可以选择每周的任意一天自由组合
**　　节假日模式**默认表示每天，工作日和节假日可以通过**系统 -> 时间属性**进行设置，选用后会根据设定的日期进行执行
![](/Project/GPCS/Resources/images/h_strategy_cycle.png)
**动作模型**可以定义一天内的执行时间，执行策略，轮询周期和轮询次数
**　　执行时间**时和分需要补0，例如8点为08:00
**　　执行策略**需要在控制功能配置系统中的控制接口功能中有相应的命令，有参数的命令点击后面的执行参数进行设置
**　　轮询周期**表示重复执行该条命令的时间，默认0表示不重复
**　　轮询次数**表示重复执行的次数，默认0表示不重复
![](/Project/GPCS/Resources/images/h_strategy_control.png)
</div>