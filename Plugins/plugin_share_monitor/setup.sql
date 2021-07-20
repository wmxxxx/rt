USE [Things+]
GO
/****** Object:  Table [dbo].[TB_Share_UserToNode]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_UserToNode]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_UserToNode](
	[F_UserID] [varchar](50) NULL,
	[F_NodeID] [varchar](50) NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_Task]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_Task]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_Task](
	[F_id] [varchar](50) NOT NULL,
	[F_name] [varchar](50) NULL,
	[F_rel_id] [varchar](50) NULL,
	[F_type] [int] NULL,
	[F_time] [varchar](50) NULL,
	[F_app] [int] NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_RunningState]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_RunningState]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_RunningState](
	[F_ID] [varchar](50) NOT NULL,
	[F_App] [varchar](50) NULL,
	[F_NodeID] [varchar](50) NULL,
	[F_Date] [varchar](50) NULL,
	[F_StartTime] [varchar](50) NULL,
	[F_EndTime] [varchar](50) NULL,
	[F_State] [varchar](50) NULL,
	[F_Info] [varchar](50) NULL,
	[F_Color] [varchar](50) NULL,
	[F_Tab] [int] NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_Relation]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_Relation]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_Relation](
	[F_id] [varchar](50) NULL,
	[F_name] [varchar](50) NULL,
	[F_group_id] [varchar](max) NULL,
	[F_cycle_id] [varchar](50) NULL,
	[F_action_id] [varchar](50) NULL,
	[F_open] [int] NULL,
	[F_push] [int] NULL,
	[F_user_id] [varchar](max) NULL,
	[F_app] [int] NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_Model]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_Model]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_Model](
	[F_id] [varchar](50) NULL,
	[F_name] [varchar](500) NULL,
	[F_start] [varchar](50) NULL,
	[F_end] [varchar](50) NULL,
	[F_type] [varchar](50) NULL,
	[F_type_val] [varchar](50) NULL,
	[F_day] [int] NULL,
	[F_app] [int] NULL,
	[F_time] [varchar](50) NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_Log]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_Log]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_Log](
	[F_id] [varchar](50) NULL,
	[F_task_id] [varchar](50) NULL,
	[F_node_id] [varchar](50) NULL,
	[F_command_name] [varchar](50) NULL,
	[F_type] [int] NULL,
	[F_content] [varchar](max) NULL,
	[F_send] [varchar](50) NULL,
	[F_app] [int] NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_Illegal]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_Illegal]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_Illegal](
	[F_id] [varchar](50) NULL,
	[F_node_id] [varchar](50) NULL,
	[F_type] [int] NULL,
	[F_msg] [varchar](500) NULL,
	[F_time] [varchar](50) NULL,
	[F_app] [int] NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_GroupToNode]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_GroupToNode]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_GroupToNode](
	[F_node_id] [bigint] NOT NULL,
	[F_group_id] [varchar](50) NOT NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_Group]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_Group]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_Group](
	[F_id] [varchar](50) NOT NULL,
	[F_name] [varchar](50) NOT NULL,
	[F_code] [varchar](50) NOT NULL,
	[F_init_time] [varchar](50) NOT NULL,
	[F_project_code] [varchar](50) NOT NULL,
 CONSTRAINT [PK_TB_Share_Group] PRIMARY KEY CLUSTERED 
(
	[F_id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_share_FlexEnergy]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_share_FlexEnergy]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_share_FlexEnergy](
	[F_id] [varchar](50) NULL,
	[F_nodeid] [varchar](50) NULL,
	[F_tpl] [varchar](50) NULL,
	[F_outTemp] [int] NULL,
	[F_outHum] [int] NULL,
	[F_temp] [int] NULL,
	[F_hum] [int] NULL,
	[F_time] [int] NULL,
	[F_energy] [varchar](50) NULL,
	[F_diff] [int] NULL,
	[F_open] [int] NULL,
	[F_date] [varchar](50) NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_ErrorState]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_ErrorState]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_ErrorState](
	[F_ID] [varchar](50) NOT NULL,
	[F_Type] [int] NULL,
	[F_Info] [varchar](50) NULL,
	[F_StartDate] [varchar](50) NULL,
	[F_CurDate] [varchar](50) NULL,
 CONSTRAINT [PK_TB_Share_ErrorState] PRIMARY KEY CLUSTERED 
(
	[F_ID] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_Config]    Script Date: 06/25/2021 09:44:59 ******/
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
/****** Object:  Table [dbo].[TB_Share_Back]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_Back]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_Back](
	[F_bid] [varchar](50) NULL,
	[F_back] [varchar](max) NULL,
	[F_time] [varchar](50) NULL,
	[F_code] [varchar](50) NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_Action]    Script Date: 06/25/2021 09:44:59 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TB_Share_Action]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TB_Share_Action](
	[F_id] [varchar](50) NULL,
	[F_action_id] [varchar](50) NULL,
	[F_time] [varchar](50) NULL,
	[F_command_id] [varchar](50) NULL,
	[F_poll_time] [int] NULL,
	[F_poll_num] [int] NULL,
	[F_value] [varchar](500) NULL
) ON [PRIMARY]
END
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[TB_Share_AccessRel]    Script Date: 06/25/2021 09:44:59 ******/
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
/****** Object:  Table [dbo].[TB_command_interface_arg]    Script Date: 06/25/2021 09:44:59 ******/
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
/****** Object:  Table [dbo].[TB_command_interface]    Script Date: 06/25/2021 09:44:59 ******/
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
/****** Object:  Table [dbo].[TB_command_impl_mx]    Script Date: 06/25/2021 09:44:59 ******/
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
/****** Object:  Table [dbo].[TB_command_impl_contion]    Script Date: 06/25/2021 09:44:59 ******/
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
/****** Object:  Table [dbo].[TB_command_impl_config]    Script Date: 06/25/2021 09:44:59 ******/
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
/****** Object:  Table [dbo].[TB_command_impl]    Script Date: 06/25/2021 09:44:59 ******/
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
