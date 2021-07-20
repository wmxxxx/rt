USE [Things+]
GO
/****** Object:  Table [dbo].[TB_Share_Task]    Script Date: 06/25/2021 09:50:14 ******/
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
/****** Object:  Table [dbo].[TB_Share_Log]    Script Date: 06/25/2021 09:50:14 ******/
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
/****** Object:  Table [dbo].[TB_Share_Back]    Script Date: 06/25/2021 09:50:14 ******/
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
