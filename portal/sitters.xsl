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
                            <div class="jumbotron">
                                <div class="row ">
                                    <h2>Search Results</h2>
                                    <a href="index.php?uri=search-display-xslt" class="btn btn-primary">Back To Search
                                    </a>
                                </div>

                                <div class="row push-bottom">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th id="name">Name</th>
                                                <th id="type">Type</th>
                                                <th id="charges">Charges (p/h)</th>
                                                <th id="location">Location</th>
                                                <th id="details">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <xsl:for-each select="sitters/sitter">
                                                <xsl:variable name="id" select="@id"/>
                                                <xsl:variable name="service" select="@service"/>

                                                <tr class="tall">
                                                    <td headers="name" class="center">
                                                        <xsl:value-of select="name/firstname"/><xsl:text> </xsl:text><xsl:value-of
                                                            select="name/lastname"/>
                                                    </td>
                                                    <td headers="type" class="center">
                                                        <xsl:value-of select="service/type"/>
                                                    </td>
                                                    <td headers="charges" class="center">
                                                        &#163;<xsl:value-of
                                                            select="service/charges"/>
                                                    </td>
                                                    <td headers="location" class="center">
                                                        <xsl:value-of select="service/location"/>
                                                    </td>
                                                    <td headers="details" class="center">
                                                        <a href="index.php?uri=sitter-detail-xslt&#38;id={$id}&#38;service={$service}"
                                                           title="View sitters details" class="btn btn-sm btn-info">
                                                            Details
                                                        </a>
                                                    </td>
                                                </tr>
                                            </xsl:for-each>
                                        </tbody>
                                    </table>
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
