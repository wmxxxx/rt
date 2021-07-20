USE [Things]
INSERT dbo.tb_A_Plugins (F_PluginCode,F_PluginName,F_PluginTag,F_PluginTypeNo,F_PluginCategory,F_IsConfig,F_ConfType,F_EnergyConfig,F_TemplateConfig,F_DeviceConfig,F_GuideMode) VALUES (1624871300,'通用控制弹性控制','plugin_share_elastic','J','1',1,'4',0,1,1,'html')
INSERT dbo.tb_A_Function (F_FunctionCode,F_FunctionName,F_FunctionTag,F_FunctionTypeNo,F_PluginCode) VALUES (1624871369,'通用控制弹性控制','fun_gpcs_elastic','J',1624871300)
INSERT dbo.tb_A_ProjectToMenu (F_ProjectNo,F_MenuCode,F_MenuTag,F_MenuName,F_MenuAbbr,F_MenuType,F_MenuIndex,F_MenuPosition,F_IsHasChild,F_ParentCode,F_FunctionCode) VALUES (2005,1618839871,'gpcs_strategy','策略管理','策略管理','1',4,'v',1,0,0)
INSERT dbo.tb_A_ProjectToMenu (F_ProjectNo,F_MenuCode,F_MenuTag,F_MenuName,F_MenuAbbr,F_MenuType,F_MenuIndex,F_MenuPosition,F_IsHasChild,F_ParentCode,F_FunctionCode) VALUES (2005,1624871499,'gpcs_elastic','弹性控制','弹性','2',4,'v',0,1618839871,1624871369)
