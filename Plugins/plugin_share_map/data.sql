USE [Things]
INSERT dbo.tb_A_Plugins (F_PluginCode,F_PluginName,F_PluginTag,F_PluginTypeNo,F_PluginCategory,F_IsConfig,F_ConfType,F_EnergyConfig,F_TemplateConfig,F_DeviceConfig,F_GuideMode) VALUES (1600000001,'通用控制地图监测','plugin_share_map','C','1',1,'4',1,1,1,'js')
INSERT dbo.tb_A_Function (F_FunctionCode,F_FunctionName,F_FunctionTag,F_FunctionTypeNo,F_PluginCode) VALUES (1610000001,'通用控制地图监测','fun_gpcs_map','C',1600000001)
INSERT dbo.tb_A_PluginEnvVar (F_PluginCode,F_EnvVarKey) VALUES (1600000001,'oTag')
INSERT dbo.tb_A_PluginEnvVar (F_PluginCode,F_EnvVarKey) VALUES (1600000006,'type')
INSERT dbo.tb_A_FunctionEnvVar (F_FunctionCode,F_EnvVarKey,F_EnvVarValue,F_PluginCode) VALUES (1610000001,'oTag','%5B%7B%27name%27%3A%27XX%u6821%u533A%27%2C%27maps%27%3A%5B%27%u672C%u90E8%27%5D%7D%5D',1600000001)
INSERT dbo.tb_A_FunctionEnvVar (F_FunctionCode,F_EnvVarKey,F_EnvVarValue,F_PluginCode) VALUES (1619083878,'type','1',1600000006)
INSERT dbo.tb_A_ProjectToMenu (F_ProjectNo,F_MenuCode,F_MenuTag,F_MenuName,F_MenuAbbr,F_MenuType,F_MenuIndex,F_MenuPosition,F_IsHasChild,F_ParentCode,F_FunctionCode) VALUES (2005,1618839782,'gpcs_map','地图监控','地图监控','1',2,'v',0,0,1610000001)