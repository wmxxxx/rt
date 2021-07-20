USE [Things+]
GO
/****** Object:  Table [dbo].[TB_Share_Illegal]    Script Date: 06/25/2021 09:32:29 ******/
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
