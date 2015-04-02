<?php

/**
 * Copyright 2014 Michael van Vliet (Leiden University), Thomas Hankemeier 
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
      </div>
      <div class="mx_footer">
        <div class="mx_footer_content">
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
            <a target="_blank" href="http://www.cosmos-fp7.eu/"><img height="40px" class="mx_logo" src="/img/cosmos/fp7.png"></a> This project is funded through European Commission COSMOS Grant EC312941
            <div>
                <a href="/documentation">API</a> | <a target="_blank" href="/ns/dcat">RDF</a>
            </div>
        </div>
      </div>
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

  </body>
</html>
