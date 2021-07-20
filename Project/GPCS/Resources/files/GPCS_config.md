> v2.3.0

<div id="gpcs\_config">
# 前置条件
#### 1.修改设备模板标识
在平台**工程管理 -> 设备模板**中修改为对应的模板标识（如下图所示）
![](/Project/GPCS/Resources/images/c_tpl.png)
包含的默认设备有：
HVAC 对应的模板标识为：**hvac2**
风机盘管 对应的模板标识为：**fjpg2**
分体空调 对应的模板标识为：**ftkt2**

由于现场物联网同步上来的设备模版参数无具体业务配置（如枚举类型等）需要手工维护也可以手工导入已配置好的模版文件，修改完需要重启系统服务（不生效再次重启）
#### 2.设置策略回调地址
在平台**控制功能配置 -> 基础配置**中将**策略回调地址**修改为物联网平台能访问到的地址（如下图所示）
![](/Project/GPCS/Resources/images/c_ip.png)
#### 3.设置计划任务
平台的计划任务在部署时会自动添加，系统的计划任务需要手动导入

在开始菜单搜索**任务计划**或使用快捷键windows + R键，输入“taskschd.msc”
右击**任务计划程序库 -> 导入任务**选择平台安装目录下**www\Projec\GPCS\通用控制策略.xml**文件，打开
点击**更改用户或组**，输入administrator，点击**检查名称**，点击确认，再次点击确认，输入系统登录密码完成导入（如下图所示）
![](/Project/GPCS/Resources/images/c_plan.png)
#### 4.开启数据压缩
打开wamp -> Apache -> httpd.conf，将**deflate\_module**、**filter\_module**、**headers\_module**前的#删除，并在文件末尾添加下面这段代码
```
<ifmodule mod_deflate.c>
AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/x-javascript application/javascript
</ifmodule>
```
![](/Project/GPCS/Resources/images/c_zip.png)
</div>
<div id="gpcs\_use">
# 功能介绍
#### 1.基础配置
**策略回调地址**默认为localhost，需要配置为物联网平台能访问到的地址
**批量任务个数**为单个批量任务下发的子任务个数上限
**日志保存周期**为通用控制系统日志、设备运行状态、违规状态信息的存储周期，可以按需要选择保存周期，默认不开启
![](/Project/GPCS/Resources/images/c_base.png)
#### 2.实时监测配置
首次进入会加载默认配置，如需自定义配置可点击清除配置按钮（如下图所示）
![](/Project/GPCS/Resources/images/c_monitor.png)
下图为设备图标和按钮图标的素材，可按需要更换
![](/Project/GPCS/Resources/images/c_icon.png)
点击分组视图配置的修改按钮，可对分组视图显示的内容进行修改，可配置多个组（推荐配置2个），系统会根据配置的条件计算对应的设备个数（如下图所示）
![](/Project/GPCS/Resources/images/c_group.png)
点击接入设备关联按钮，可为管理节点关联外接设备，外接设备参数会在分组视图和节点视图中展示（如下图所示）
![](/Project/GPCS/Resources/images/c_access.png)
点击节点视图配置的修改按钮，可对节点视图显示的内容进行修改（如下图所示）
![](/Project/GPCS/Resources/images/c_node.png)
点击设备视图配置的修改按钮，可对设备视图显示的内容进行修改（如下图所示）
![](/Project/GPCS/Resources/images/c_device.png)
#### 3.控制接口定义
部署时会添加设备对应的命令接口，可以根据需要手动增加
点击新增接口，可以添加命令接口，命令会按照接口描述进行排序。点击参数配置按钮可以为命令增加传入的参数（如下图所示）
![](/Project/GPCS/Resources/images/c_ready.png)
#### 4.控制接口实现
点击业务实现 -> 新增服务，可以添加控制接口定义中的命令
点击业务配置，可以为此命令配置参数，参数分为设备模板自身的参数、虚拟参数、传入参数（接口定义中配置的）
**默认值**会在组装命令时赋值给输出变量标识，为设备变量时会赋值为实时的值，为传入参数时会赋值为传入的值
**处理类型**实现了一些简单的算法对默认值进行处理。
**　　表达式处理**可以进行加减乘除和三目运算，可以用{}来获取参数的值进行运算
**　　系统时间**获取当前的时间作为默认值
**输出命令标识**为空则该条参数为写入命令，不为空则该条参数为自定义命令
**输出变量标识**为空则该条参数不会进行命令组装，不论参数名为什么最终会用输出变量标识来组装命令
点击前置条件，可以为此命令增加一些条件，满足这些条件的情况下才会下发此命令
![](/Project/GPCS/Resources/images/c_go.png)
</div>