USE [Things+]
GO
/****** Object:  Table [dbo].[TB_Share_Config]    Script Date: 06/28/2021 15:02:26 ******/
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
