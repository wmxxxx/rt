USE [Things]
INSERT dbo.tb_A_Plugins (F_PluginCode,F_PluginName,F_PluginTag,F_PluginTypeNo,F_PluginCategory,F_IsConfig,F_ConfType,F_EnergyConfig,F_TemplateConfig,F_DeviceConfig,F_GuideMode) VALUES (1600000006,'ͨ�ÿ���Υ��ģ��','plugin_share_illegal','J','1',0,'',0,0,0,'html')
INSERT dbo.tb_A_Function (F_FunctionCode,F_FunctionName,F_FunctionTag,F_FunctionTypeNo,F_PluginCode) VALUES (1619083878,'ͨ�ÿ���ʱ��ģ��','fun_gpcs_illegal','J',1600000006)
INSERT dbo.tb_A_ProjectToMenu (F_ProjectNo,F_MenuCode,F_MenuTag,F_MenuName,F_MenuAbbr,F_MenuType,F_MenuIndex,F_MenuPosition,F_IsHasChild,F_ParentCode,F_FunctionCode) VALUES (2005,1618839886,'gpcs_warning','Υ�����','Υ����� ','1',5,'v',1,0,0)
INSERT dbo.tb_A_ProjectToMenu (F_ProjectNo,F_MenuCode,F_MenuTag,F_MenuName,F_MenuAbbr,F_MenuType,F_MenuIndex,F_MenuPosition,F_IsHasChild,F_ParentCode,F_FunctionCode) VALUES (2005,1619083650,'gpcs_illegal','Υ��ģ��','Υ��ģ��','2',1,'v',0,1618839886,1619083878)
