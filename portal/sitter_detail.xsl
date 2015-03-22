<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
                <title>Sitters Search Portal</title>
                <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css"/>
                <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap-theme.min.css"/>
                <link rel="stylesheet" href="styles.css"/>
            </head>
            <body>
                <div class="container">
                    <div class="row clearfix">
                        <div class="col-md-10 column">
                            <div class="navbar navbar-default">
                                <div class="navbar-header">
                                    <a class="navbar-brand" href="./">Sitters Search Portal</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-10 column">
                            <h1>Sitter Details</h1>

                            <div class="jumbotron">
                                <h2>Information</h2>

                                <div class="row push-bottom">
                                    <div class="col-sm-2">
                                        <strong>Full Name:</strong>
                                    </div>
                                    <div class="col-sm-7">
                                        <xsl:value-of select="sitter_detail/name/firstname"/><xsl:text> </xsl:text><xsl:value-of
                                            select="sitter_detail/name/lastname"/>
                                    </div>
                                </div>

                                <div class="row push-bottom">
                                    <div class="col-sm-2">
                                        <strong>Type:</strong>
                                    </div>
                                    <div class="col-sm-7">
                                        <xsl:value-of select="sitter_detail/service/type"/>
                                    </div>
                                </div>

                                <div class="row push-bottom">
                                    <div class="col-sm-2">
                                        <strong>Email:</strong>
                                    </div>
                                    <div class="col-sm-7">
                                        <xsl:value-of select="sitter_detail/email"/>
                                    </div>
                                </div>

                                <div class="row push-bottom">
                                    <div class="col-sm-2">
                                        <strong>Phone:</strong>
                                    </div>
                                    <div class="col-sm-7">
                                        <xsl:value-of select="sitter_detail/phone"/>
                                    </div>
                                </div>

                                <div class="row push-bottom">
                                    <div class="col-sm-2">
                                        <strong>Charges:</strong>
                                    </div>
                                    <div class="col-sm-7">
                                        &#163;<xsl:value-of select="sitter_detail/service/charges"/> p/h
                                    </div>
                                </div>

                                <div class="row push-bottom">
                                    <div class="col-sm-2">
                                        <strong>Location:</strong>
                                    </div>
                                    <div class="col-sm-7">
                                        <xsl:value-of select="sitter_detail/service/location"/>
                                    </div>
                                </div>

                                <div class="row push-bottom">
                                    <div class="col-sm-2">
                                        <strong>Availability:</strong>
                                    </div>
                                    <div class="col-sm-7">
                                        <xsl:value-of select="sitter_detail/service/availability"/>
                                    </div>
                                </div>

                                <div class="row push-bottom">
                                    <div class="col-sm-2">
                                        <strong>Description:</strong>
                                    </div>
                                    <div class="col-sm-7">
                                        <xsl:value-of select="sitter_detail/service/description"/>
                                    </div>
                                </div>
                            </div>


                            <div class="jumbotron">
                                <h2>Pictures</h2>

                                <div class="row push-bottom">
                                    <xsl:for-each select="sitter_detail/service/images/image">
                                        <xsl:variable name="url" select="image_url" />

                                        <div class="col-sm-6 push-bottom push-top">
                                            <img src="{$url}" height="200"/>
                                        </div>
                                    </xsl:for-each>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <script type="text/javascript" src="assets/jquery/dist/jquery.min.js"></script>
                <script type="text/javascript" src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
