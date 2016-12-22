<?php header("Content-type: text/xml; charset=utf-8"); ?>
<?xml version='1.0' encoding='UTF-8'?>
<note>
<from>Jani</from>
<to>Tove</to>
<message>Remember me this weekend</message>
</note>


<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
xmlns="http://purl.org/rss/1.0/"
xmlns:dc="http://purl.org/dc/elements/1.1/"
xmlns:syn="http://purl.org/rss/1.0/modules/syndication/">

    <channel rdf:about="http://www.nada.kth.se/media/Theses/">
        <title>Examensarbeten medieteknik</title>
        <link>http://www.nada.kth.se/media/Theses/</link>
        <description>Examensarbeten inom medieteknik.</description>
        <dc:language>sv</dc:language>
        <?php
        $dateStamp=date("c");
        echo "<dc:date>$dateStamp</dc:date>"
        ?>
        <dc:publisher>KTH/Nada/Media</dc:publisher>
        <dc:creator>bjornh@kth.se</dc:creator>
        <syn:updatePeriod>daily</syn:updatePeriod>
        <syn:updateFrequency>1</syn:updateFrequency>
        <syn:updateBase>2006-01-01T00:00+00:00</syn:updateBase>
        <items>
            <rdf:Seq>
                <?php
                $link=mysqli_connect('localhost', 'rsslab', 'rsslab', 'rsslab');
                if (mysqli_connect_errno()) {
                    printf("Connection failed: %s\n", mysqli_connect_error());
                    exit();
                }
                $query="SELECT link FROM exjobbsfeed";
                if (($result=mysqli_query($link, $query)) === false) {
                    printf("Query failed: %s<br />\n%s", $query, mysqli_error($link));
                    exit();
                }
                $returnstring='';
                while ($line=$result->fetch_object()) {
                    $resource = $line->link;
                    $patterns = '/\s/';
                    if ((preg_match($patterns,$resource))==1){
                        $replacements = '%20';
                        $resource = preg_replace($patterns, $replacements, $resource);
                    }
                    $returnstring .= "<rdf:li rdf:resource='$resource'/>";
                }
                mysqli_free_result($result);
                print utf8_encode($returnstring);
                ?>
            </rdf:Seq>
        </items>
        <image rdf:resource="http://www.nada.kth.se/media/images/kth.png"/>
    </channel>
    <?php
        $link=mysqli_connect('localhost', 'rsslab', 'rsslab', 'rsslab');
        if (mysqli_connect_errno()) {
            printf("Connection failed: %s\n", mysqli_connect_error());
            exit();
        }
        $query="SELECT link, title, description, creator, feeddate FROM exjobbsfeed ORDER BY feeddate ASC";
        if (($result=mysqli_query($link, $query)) === false) {
            printf("Query failed: %s <br />\n%s", $query, mysqli_error($link));
            exit();
        }
        $returnstring='';
        while ($line=$result->fetch_object()) {
            $resource = $line->link;
            $title = $line->title;
            $description = $line->description;
            $creator = $line->creator;
            $feeddate = $line->feeddate;
            $patterns = array('/\s/', '/\&/');
            $replacements = array('&amp;', '%20');
            if ((preg_match($patterns[0], $resource))==1) {
                $resource = preg_replace($patterns[0], $replacements[1], $resource);
            }
            if ((preg_match($patterns[1], $title))==1) {
                $title = preg_replace($patterns[1], $replacements[0], $title);
            }
            if ((preg_match($patterns[1], $description))==1) {
                $description = preg_replace($patterns[1], $replacements[0], $description);
            }
            $timestamp = strtotime($feeddate);
            $datestamp = date('c', $timestamp);
            $returnstring .= "<item rdf:about='$resource'>";
            $returnstring .= "<title>$title</title>";
            $returnstring .= "<link>$resource</link>";
            $returnstring .= "<description>$description</description>";
            $returnstring .= "<dc:creator>$creator</dc:creator>";
            $returnstring .= "<dc:date>$datestamp</dc:date>";
            $returnstring .= "</item>";
        }
        mysqli_free_result($result);
        print utf8_encode($returnstring);
    ?>
</rdf:RDF>
