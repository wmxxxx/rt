USE [Things+]
GO
/****** Object:  Table [dbo].[TB_Share_Model]    Script Date: 06/30/2021 08:33:23 ******/
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
