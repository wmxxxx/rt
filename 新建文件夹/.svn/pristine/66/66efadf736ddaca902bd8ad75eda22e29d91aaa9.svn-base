USE [Things+]
GO
/****** Object:  Table [dbo].[TB_Share_Config]    Script Date: 06/25/2021 09:15:38 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_Config]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_Config](
	[F_Type] [varchar](50) NULL,
	[F_App] [varchar](50) NULL,
	[F_Config] [varchar](max) NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
INSERT [dbo].[TB_Share_Config] ([F_Type], [F_App], [F_Config]) VALUES (N'base_v2', N'0', N'{"ip":"http://localhost","num":20,"clear":"0"}')
/****** Object:  Table [dbo].[TB_Share_AccessRel]    Script Date: 06/25/2021 09:15:38 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_AccessRel]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_AccessRel](
	[F_App] [varchar](50) NULL,
	[F_GroupNo] [varchar](50) NULL,
	[F_GroupID] [varchar](50) NULL,
	[F_DeviceNo] [varchar](50) NULL,
	[F_DeviceID] [varchar](50) NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_command_interface_arg]    Script Date: 06/25/2021 09:15:38 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_command_interface_arg]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_command_interface_arg](
	[F_id] [varchar](50) NOT NULL,
	[F_info_id] [varchar](50) NULL,
	[F_name] [varchar](50) NULL,
	[F_code] [varchar](50) NULL,
	[F_type] [varchar](50) NULL,
	[F_typecontent] [varchar](400) NULL,
 CONSTRAINT [PK_TB_command_intetface_arg] PRIMARY KEY CLUSTERED 
(
	[F_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
INSERT [dbo].[TB_command_interface_arg] ([F_id], [F_info_id], [F_name], [F_code], [F_type], [F_typecontent]) VALUES (N'b0253aca11eb87bae4570f4a7b4c71b6', N'f8921e763085797fc63b0448b93ea10b', N'设定温度', N'temp', N'num', N'18-30')
/****** Object:  Table [dbo].[TB_command_interface]    Script Date: 06/25/2021 09:15:38 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_command_interface]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_command_interface](
	[F_id] [varchar](50) NOT NULL,
	[F_name] [varchar](50) NULL,
	[F_remark] [varchar](500) NULL,
 CONSTRAINT [PK_TB_command_interface] PRIMARY KEY CLUSTERED 
(
	[F_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
INSERT [dbo].[TB_command_interface] ([F_id], [F_name], [F_remark]) VALUES (N'3fbc403827abe4f36defb0d1da815743', N'关机', N'104')
INSERT [dbo].[TB_command_interface] ([F_id], [F_name], [F_remark]) VALUES (N'8f797441bf671ed4ea1aa4158dea908a', N'制热', N'103')
INSERT [dbo].[TB_command_interface] ([F_id], [F_name], [F_remark]) VALUES (N'910f0b27e24a332bcad046c054b3b6dd', N'制冷', N'102')
INSERT [dbo].[TB_command_interface] ([F_id], [F_name], [F_remark]) VALUES (N'971246f5706ef7f8cf06ee01074bce1d', N'解锁', N'108')
INSERT [dbo].[TB_command_interface] ([F_id], [F_name], [F_remark]) VALUES (N'9ff8ec76a97d331152e5f5dc98d4d625', N'开机', N'105')
INSERT [dbo].[TB_command_interface] ([F_id], [F_name], [F_remark]) VALUES (N'bb8a1308cfdd3220c2aaddaa76e86d7f', N'断电', N'106')
INSERT [dbo].[TB_command_interface] ([F_id], [F_name], [F_remark]) VALUES (N'c6117a3cdaed6ccd45d6a81dd3e2e80c', N'锁定', N'107')
INSERT [dbo].[TB_command_interface] ([F_id], [F_name], [F_remark]) VALUES (N'f8921e763085797fc63b0448b93ea10b', N'调温', N'101')
/****** Object:  Table [dbo].[TB_command_impl_mx]    Script Date: 06/25/2021 09:15:38 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_command_impl_mx]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_command_impl_mx](
	[F_id] [varchar](50) NOT NULL,
	[F_info_id] [varchar](50) NULL,
	[F_face_id] [varchar](50) NULL,
 CONSTRAINT [PK_TB_command_impl_mx] PRIMARY KEY CLUSTERED 
(
	[F_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'04a84a7709843cda16982ffbff19ecba', N'3a11998ee87e0c72458e3504648ff00e', N'9ff8ec76a97d331152e5f5dc98d4d625')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'2a25537e4416dee9fa4f24a2cfe52db3', N'93d1cc86373d7f72dcc569badd759586', N'910f0b27e24a332bcad046c054b3b6dd')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'2ec2a862b77e8e39e021daaf508a5f9d', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'9ff8ec76a97d331152e5f5dc98d4d625')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'5abf8100a81893433dcc3cf9d7e66df0', N'3a11998ee87e0c72458e3504648ff00e', N'910f0b27e24a332bcad046c054b3b6dd')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'60881c86fa78b70af39eeac41faa23f3', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'bb8a1308cfdd3220c2aaddaa76e86d7f')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'889218361c866138e1c50623d136ac77', N'93d1cc86373d7f72dcc569badd759586', N'3fbc403827abe4f36defb0d1da815743')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'8944dd6dd7b272d8eb05a442d8265e21', N'93d1cc86373d7f72dcc569badd759586', N'f8921e763085797fc63b0448b93ea10b')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'8a3de2a0608b6e6897f8630241a0873a', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'8f797441bf671ed4ea1aa4158dea908a')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'912a5c1759e358745dfe044c804a4370', N'93d1cc86373d7f72dcc569badd759586', N'9ff8ec76a97d331152e5f5dc98d4d625')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'a2251ff86c2495bbb7d8b9175a6d8421', N'3a11998ee87e0c72458e3504648ff00e', N'3fbc403827abe4f36defb0d1da815743')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'ab624a9dc413c5fbe3abe788a32906eb', N'93d1cc86373d7f72dcc569badd759586', N'8f797441bf671ed4ea1aa4158dea908a')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'ac75d93f9c2188657fac48f63b976c10', N'93d1cc86373d7f72dcc569badd759586', N'c6117a3cdaed6ccd45d6a81dd3e2e80c')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'bcebcb64ee3534c22c3732386b282f87', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'910f0b27e24a332bcad046c054b3b6dd')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'cdaac87a32d18cba8e40a1e2ef1adad2', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'f8921e763085797fc63b0448b93ea10b')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'd9f02149618ba25ce89b496b6cb7e783', N'3a11998ee87e0c72458e3504648ff00e', N'8f797441bf671ed4ea1aa4158dea908a')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'ea166bb9459d654b8a8e5bdc27ba63de', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'3fbc403827abe4f36defb0d1da815743')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'f514d3e8db38c864ce729593cba0eaca', N'3a11998ee87e0c72458e3504648ff00e', N'f8921e763085797fc63b0448b93ea10b')
INSERT [dbo].[TB_command_impl_mx] ([F_id], [F_info_id], [F_face_id]) VALUES (N'f881d91e210384190377ae4c2df10b89', N'93d1cc86373d7f72dcc569badd759586', N'971246f5706ef7f8cf06ee01074bce1d')
/****** Object:  Table [dbo].[TB_command_impl_contion]    Script Date: 06/25/2021 09:15:38 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_command_impl_contion]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_command_impl_contion](
	[F_id] [varchar](50) NOT NULL,
	[F_mx_id] [varchar](50) NULL,
	[F_info_id] [varchar](50) NULL,
	[F_code] [varchar](50) NULL,
	[F_con] [varchar](50) NULL,
	[F_value] [varchar](50) NULL,
 CONSTRAINT [PK_TB_command_impl_contion] PRIMARY KEY CLUSTERED 
(
	[F_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
INSERT [dbo].[TB_command_impl_contion] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_con], [F_value]) VALUES (N'b073a54d9d793dbdd45d54ff5a8373cd', N'ac75d93f9c2188657fac48f63b976c10', N'93d1cc86373d7f72dcc569badd759586', N'LockStatus', N'等于', N'0')
INSERT [dbo].[TB_command_impl_contion] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_con], [F_value]) VALUES (N'c1d0e3000d19883fa79e6f713d659f19', N'f881d91e210384190377ae4c2df10b89', N'93d1cc86373d7f72dcc569badd759586', N'LockStatus', N'不等于', N'0')
/****** Object:  Table [dbo].[TB_command_impl_config]    Script Date: 06/25/2021 09:15:38 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_command_impl_config]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_command_impl_config](
	[F_id] [varchar](50) NOT NULL,
	[F_mx_id] [varchar](50) NULL,
	[F_info_id] [varchar](50) NULL,
	[F_code] [varchar](50) NULL,
	[F_value] [varchar](50) NULL,
	[F_type] [varchar](50) NULL,
	[F_typecontent] [varchar](400) NULL,
	[F_command_code] [varchar](50) NULL,
	[F_value_code] [varchar](50) NULL,
 CONSTRAINT [PK_TB_command_impl_config] PRIMARY KEY CLUSTERED 
(
	[F_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'028f83bdf9fce94ee9843093dd63ef5e', N'60881c86fa78b70af39eeac41faa23f3', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'0', N'no', N'', N'SetCurCmd', N'ModeCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'0e9158186b3e76247a7f77ab3b62ade0', N'cdaac87a32d18cba8e40a1e2ef1adad2', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'arg-temp', N'0', N'no', N'', N'SetCurCmd', N'Temp')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'0f12e27b5fece8080b4b1702f7342546', N'ea166bb9459d654b8a8e5bdc27ba63de', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'0', N'no', N'', N'SetCurCmd', N'ModeCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'11af18e949147038130140ecee92e855', N'60881c86fa78b70af39eeac41faa23f3', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'0', N'no', N'', N'SetCurCmd', N'Temp')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'153aa523ce4678464980c48886f6ef97', N'2ec2a862b77e8e39e021daaf508a5f9d', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'0', N'no', N'', N'SetCurCmd', N'ModeCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'18d99a1906ba9e7870f7a903db7389b7', N'5abf8100a81893433dcc3cf9d7e66df0', N'3a11998ee87e0c72458e3504648ff00e', N'', N'1', N'no', N'', N'', N'ModeSet')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'1b777750f69c12677e059b09fc49327f', N'912a5c1759e358745dfe044c804a4370', N'93d1cc86373d7f72dcc569badd759586', N'', N'1', N'no', N'', N'', N'StartStopCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'1e24a7bd522a0e22a9fc17ae25e66f4c', N'd9f02149618ba25ce89b496b6cb7e783', N'3a11998ee87e0c72458e3504648ff00e', N'', N'8', N'no', N'', N'', N'ModeSet')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'1e4743b877414e02a045bf32affcfe27', N'04a84a7709843cda16982ffbff19ecba', N'3a11998ee87e0c72458e3504648ff00e', N'', N'StartStopStatus', N'no', N'', N'', N'StartStopStatus')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'2071cf78971b0ee7a17b7a90177132a3', N'bcebcb64ee3534c22c3732386b282f87', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'2', N'no', N'', N'SetCurCmd', N'ModeCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'2082acb8dce8bafdd50bf1d93455bc34', N'8a3de2a0608b6e6897f8630241a0873a', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'255', N'no', N'', N'SetCurCmd', N'CurCmdTime')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'20b6d8e621ea43cd5e19147ba1e94630', N'2a25537e4416dee9fa4f24a2cfe52db3', N'93d1cc86373d7f72dcc569badd759586', N'', N'20', N'no', N'', N'', N'StartStopCmdTemp')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'2c29f2a49cd4f6c6dcecbca3d70f1818', N'8a3de2a0608b6e6897f8630241a0873a', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'3', N'no', N'', N'SetCurCmd', N'ModeCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'4bb3995477b43a8a88cae3dd98ec4158', N'60881c86fa78b70af39eeac41faa23f3', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'255', N'no', N'', N'SetCurCmd', N'CurCmdTime')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'50e13b9d864bcb2992d34b026fa6810a', N'2ec2a862b77e8e39e021daaf508a5f9d', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'255', N'no', N'', N'SetCurCmd', N'CurCmdTime')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'51077593baa810c8a7980842e3372408', N'8a3de2a0608b6e6897f8630241a0873a', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'4', N'no', N'', N'SetCurCmd', N'StartStopCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'55ceae62b6d7f5dca8a345378d3bef4e', N'2ec2a862b77e8e39e021daaf508a5f9d', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'3', N'no', N'', N'SetCurCmd', N'StartStopCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'60f2a586ec0873d79d5b4ef9bf955449', N'ac75d93f9c2188657fac48f63b976c10', N'93d1cc86373d7f72dcc569badd759586', N'', N'1', N'no', N'', N'', N'LockCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'62fe369a8ac897b9016fe7d6e6fe6a89', N'2ec2a862b77e8e39e021daaf508a5f9d', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'0', N'no', N'', N'SetCurCmd', N'Temp')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'666a1d59cd9b15832622eb65f2060c83', N'f881d91e210384190377ae4c2df10b89', N'93d1cc86373d7f72dcc569badd759586', N'', N'0', N'no', N'', N'', N'LockCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'67dadeb5827dfe57987027e7a768ca35', N'ea166bb9459d654b8a8e5bdc27ba63de', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'0', N'no', N'', N'SetCurCmd', N'Temp')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'68a61c298f73fd12a024941919b74331', N'60881c86fa78b70af39eeac41faa23f3', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'2', N'no', N'', N'SetCurCmd', N'StartStopCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'6ab9dbbba27626b1fdee4cfad08a1e94', N'ab624a9dc413c5fbe3abe788a32906eb', N'93d1cc86373d7f72dcc569badd759586', N'', N'26', N'no', N'', N'', N'StartStopCmdTemp')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'9158b65e05b5e8bd67bc9715ef210ddb', N'a2251ff86c2495bbb7d8b9175a6d8421', N'3a11998ee87e0c72458e3504648ff00e', N'', N'0', N'no', N'', N'', N'StartStopStatus')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'94c67311c41c1c9e13d056a4e194ba26', N'2a25537e4416dee9fa4f24a2cfe52db3', N'93d1cc86373d7f72dcc569badd759586', N'', N'2', N'no', N'', N'', N'StartStopCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'9a02f0747659a410a711becf289d2f07', N'cdaac87a32d18cba8e40a1e2ef1adad2', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'4', N'no', N'', N'SetCurCmd', N'StartStopCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'ae7f7211ff925eac021d6a684cf1639c', N'ea166bb9459d654b8a8e5bdc27ba63de', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'1', N'no', N'', N'SetCurCmd', N'StartStopCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'af9df57535c551d4393c912a66a0bb0b', N'889218361c866138e1c50623d136ac77', N'93d1cc86373d7f72dcc569badd759586', N'', N'0', N'no', N'', N'', N'StartStopCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'b1d1b9d7fff93f286f57b8b7b35509b0', N'cdaac87a32d18cba8e40a1e2ef1adad2', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'mb-ModeCmd', N'0', N'no', N'', N'SetCurCmd', N'ModeCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'baafbfe22bbb9f9e303a85de6ad9515c', N'bcebcb64ee3534c22c3732386b282f87', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'255', N'no', N'', N'SetCurCmd', N'CurCmdTime')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'c260e6ded5ca8b50a142971dc16f5193', N'cdaac87a32d18cba8e40a1e2ef1adad2', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'255', N'no', N'', N'SetCurCmd', N'CurCmdTime')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'c2f83552b7ef8f7abff63c4ce7622b8e', N'f514d3e8db38c864ce729593cba0eaca', N'3a11998ee87e0c72458e3504648ff00e', N'arg-temp', N'0', N'no', N'', N'', N'TempSet')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'cc87b0ed5525a44e4d9376659e667e72', N'bcebcb64ee3534c22c3732386b282f87', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'26', N'no', N'', N'SetCurCmd', N'Temp')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'cfeaa6f3cd20b14e593ebe8b8cf31319', N'8944dd6dd7b272d8eb05a442d8265e21', N'93d1cc86373d7f72dcc569badd759586', N'arg-temp', N'0', N'no', N'', N'', N'StartStopCmdTemp')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'e0766b4c1070c465fd941c02068e96d5', N'8a3de2a0608b6e6897f8630241a0873a', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'20', N'no', N'', N'SetCurCmd', N'Temp')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'e27a9bc2f3152adae5c75fbb3b9363f0', N'ea166bb9459d654b8a8e5bdc27ba63de', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'255', N'no', N'', N'SetCurCmd', N'CurCmdTime')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'eb9d0faee26e3d9816da23e1c0b1daf8', N'bcebcb64ee3534c22c3732386b282f87', N'11ecaa273f5529ae5bb474d9a5dcbe31', N'', N'4', N'no', N'', N'SetCurCmd', N'StartStopCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'f6f781325bdc2f16af832e630b45496b', N'ab624a9dc413c5fbe3abe788a32906eb', N'93d1cc86373d7f72dcc569badd759586', N'', N'3', N'no', N'', N'', N'StartStopCmd')
INSERT [dbo].[TB_command_impl_config] ([F_id], [F_mx_id], [F_info_id], [F_code], [F_value], [F_type], [F_typecontent], [F_command_code], [F_value_code]) VALUES (N'f87b97f3bd1cb40fe27c22483d8ed260', N'8944dd6dd7b272d8eb05a442d8265e21', N'93d1cc86373d7f72dcc569badd759586', N'', N'0', N'no', N'{ModeStatus}==1?3:2', N'', N'StartStopCmd')
/****** Object:  Table [dbo].[TB_command_impl]    Script Date: 06/25/2021 09:15:38 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_command_impl]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_command_impl](
	[F_id] [varchar](50) NOT NULL,
	[F_template_id] [varchar](50) NULL,
	[F_template_name] [varchar](50) NULL,
 CONSTRAINT [PK_TB_command_impl] PRIMARY KEY CLUSTERED 
(
	[F_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
INSERT [dbo].[TB_command_impl] ([F_id], [F_template_id], [F_template_name]) VALUES (N'11ecaa273f5529ae5bb474d9a5dcbe31', N'1616750731', N'分体空调')
INSERT [dbo].[TB_command_impl] ([F_id], [F_template_id], [F_template_name]) VALUES (N'3a11998ee87e0c72458e3504648ff00e', N'1616750648', N'HVAC')
INSERT [dbo].[TB_command_impl] ([F_id], [F_template_id], [F_template_name]) VALUES (N'93d1cc86373d7f72dcc569badd759586', N'1619444835', N'风机盘管控制器')
