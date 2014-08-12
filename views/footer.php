<?php

/**
 * Copyright 2014 Michael van Vliet (Leiden University), Thomas Hankeijer 
 * (Leiden University)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/

?>

            
            </div> <!-- #main -->
        </div> <!-- #main-container -->

        <div class="footer-container">
            <footer class="wrapper">
                <?php
                    $socialUrl = 'http%3A%2F%2Fmetabolomexchange.org';
                    $socialTitle = 'MetabolomeXchange,%20an%20international%20initiative%20to%20promote%20and%20facilitate%20sharing%20of%20Metabolomics%20data.';
                    $socialSiteUrl = $socialUrl;
                    $socialSiteDescription = $socialTitle;
                    echo '<ul class="share-buttons">
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u='.$socialSiteUrl.'&t=MetabolomeXchange" target="_blank" onclick="window.open(\'https://www.facebook.com/sharer/sharer.php?u=\' + encodeURIComponent(document.URL) + \'&t=\' + encodeURIComponent(document.URL)); return false;"><img src="/img/flat_web_icon_set/black/Facebook.png"></a></li>
                            <li><a href="https://twitter.com/intent/tweet?source='.$socialSiteUrl.'&text=MetabolomeXchange: http%3A%2F%2Fmetabolomexchange.org" target="_blank" title="Tweet" onclick="window.open(\'https://twitter.com/intent/tweet?text=\' + encodeURIComponent(document.title) + \': \' + encodeURIComponent(document.URL) + \' '.urlencode('#').'metabolomics\' + \' '.urlencode('@').'metabolomexchan\'); return false;"><img src="/img/flat_web_icon_set/black/Twitter.png"></a></li>
                            <li><a href="https://plus.google.com/share?url='.$socialUrl.'" target="_blank" title="Share on Google+" onclick="window.open(\'https://plus.google.com/share?url=\' + encodeURIComponent(document.URL)); return false;"><img src="/img/flat_web_icon_set/black/Google+.png"></a></li>
                            <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.$socialSiteUrl.'&title='.$socialSiteDescription.'.&source=http%3A%2F%2Fmetabolomexchange.org" target="_blank" title="Share on LinkedIn" onclick="window.open(\'http://www.linkedin.com/shareArticle?mini=true&url=\' + encodeURIComponent(document.URL) + \'&title=\' +  encodeURIComponent(document.title)\'); return false;"><img src="/img/flat_web_icon_set/black/LinkedIn.png"></a></li>
                            <li><a href="mailto:?subject=MetabolomeXchange&body='.$socialSiteDescription.': http%3A%2F%2Fmetabolomexchange.org" target="_blank" title="Email" onclick="window.open(\'mailto:?subject=\' + encodeURIComponent(document.title) + \'&body=\' +  encodeURIComponent(document.URL)); return false;"><img src="/img/flat_web_icon_set/black/Email.png"></a></li>
                        </ul>';
                ?>                
                <p><img src="/img/cosmos/fp7.png"> This project is funded through European Commission COSMOS Grant EC312941</p>
                <p style="text-align: center;"><a style="color: white;" target="_blank" href="http://leidenuniv-lacdr-abs.github.io/metabolomexchange/">Github.io build: <!--BUILD--><a/></p>                            
            </footer>
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.11.0.min.js"><\/script>')</script>
        <script src="js/main.js"></script>

        <script type='text/javascript'>
                var xmlHttp = null;
                xmlHttp = new XMLHttpRequest();
                xmlHttp.open( "GET", '/update/feeds', true );
                xmlHttp.send( null );
        </script>

        <script src="/js/vendor/jquery.tagcloud.js"></script>
        <script type='text/javascript'>
            $.fn.tagcloud.defaults = {
                size: {start: 10, end: 22, unit: 'pt'},
                color: {start: '#7faf8d', end: '#2a7640'}
            };

            $(function () {
              $('#tc a').tagcloud();
            });
        </script>
    </body>
</html>
