USE [Things+]
GO
/****** Object:  Table [dbo].[TB_Share_Relation]    Script Date: 06/25/2021 09:22:21 ******/
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
/****** Object:  Table [dbo].[TB_Share_Model]    Script Date: 06/25/2021 09:22:21 ******/
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
/****** Object:  Table [dbo].[TB_Share_Action]    Script Date: 06/25/2021 09:22:21 ******/
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
