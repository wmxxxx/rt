USE [Things]
INSERT dbo.tb_A_Plugins (F_PluginCode,F_PluginName,F_PluginTag,F_PluginTypeNo,F_PluginCategory,F_IsConfig,F_ConfType,F_EnergyConfig,F_TemplateConfig,F_DeviceConfig,F_GuideMode) VALUES (1600000012,'通用控制配置','plugin_share_config','J','1',0,'',0,1,0,'html')
INSERT dbo.tb_A_Function (F_FunctionCode,F_FunctionName,F_FunctionTag,F_FunctionTypeNo,F_PluginCode) VALUES (1610000012,'通用控制配置','fun_share_config','J',1600000012)
SET IDENTITY_INSERT tb_A_Project ON
INSERT dbo.tb_A_Project (F_ProjectNo,F_ProjectName,F_ProjectAbbr,F_ProjectTag,F_ProjectType,F_ProjectIndex,F_ProjectColor,F_ProjectDes,F_ProjectFrame,F_SystemFunction,F_GuideFunction) VALUES (9999,'控制功能配置','功能配置','CMCS','系统工具',19,'#0C578F','',3,'0','1615372398')
SET IDENTITY_INSERT tb_A_Project OFF
INSERT dbo.tb_A_ProjectToMenu (F_ProjectNo,F_MenuCode,F_MenuTag,F_MenuName,F_MenuAbbr,F_MenuType,F_MenuIndex,F_MenuPosition,F_IsHasChild,F_ParentCode,F_FunctionCode) VALUES (9999,1618929178,'menu_config_v2','配置','配置','1',2,'v',0,0,1610000012)
