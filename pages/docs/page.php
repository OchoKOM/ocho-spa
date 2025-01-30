<div class="content">
        <h2>Quick documentation</h2>
        <div>
          <div>GitHub Repo: <a href="https://github.com/OchoKOM/ocho-spa" class="string">https://github.com/OchoKOM/ocho-spa</a></div>
          <div>Full documentation : <a class="string" href="https://ochokom.github.io/ocho-spa-docs/" target="_blank" rel="noopener noreferrer">https://ochokom.github.io/ocho-spa-docs/</a></div>
        </div>
        <h3>Features</h3>
        <pre><code><span class="keymethod">&lt;?php</span>
<span class="comment">// Handling automatic extensions</span>
<span class="keyword">if</span> (<span class="method">file_exists</span>(<span class="identifier">$phpFile</span>)) {
    <span class="method">include</span>(<span class="identifier">$phpFile</span>); <span class="comment">// Ex: /about → /about.php</span>
}</code></pre>

        <h3>Usage</h3>
        <ul>
            <li><strong>Pages</strong> : Add folders in <span class="identifier">pages/</span> for each page of the application. Insert a <span class="identifier">page.php</span> or <span class="identifier">layout.php</span> file for the HTML content.</li>
            <li><strong>Navigation</strong> : Click on links to navigate. Pages will load dynamically without a full reload.</li>
            <li><strong>API</strong> : API requests are managed by <span class="identifier">OchoClient</span> in <span class="identifier">ocho-api.js</span>. For more information visit the repo <a href="https://github.com/OchoKOM/xhr" class="string">https://github.com/OchoKOM/xhr</a></li>
            <li><strong>Redirections</strong> : Use the function <span class="tag-name">spa_redirect()</span> to redirect to another route.</li>
        </ul>
        <h3>Configurations</h3>
        <h4>Page Structure</h4>
        <ul>
            <li>
                <strong>Adding Pages</strong>
                <div>In the <span class="identifier">pages/</span> directory, you add directories that will contain your pages</div>
                <strong class="identifier">Structure:</strong>
                <pre><code><span class="tag-name">pages/</span> <span class="comment"># Accessible via the path / (the root)</span>
├─ <span class="identifier">metadata.json</span> <span class="comment"># Default metadata</span>
├─ <span class="identifier">page.php</span> <span class="comment"># Main content</span>
├─ <span class="identifier">layout.php</span> <span class="comment"># Default layout</span>
└─ <span class="tag-name">your-directory/</span> <span class="comment"># Accessible via the path /your-directory</span>
   ├─ <span class="identifier">metadata.json</span> <span class="comment"># Page metadata</span>
   ├─ <span class="identifier">page.php</span> <span class="comment"># HTML content of the page</span>
   ├─ <span class="identifier">layout.php</span> <span class="comment"># Page layout (optional)</span>
   └─ <span class="tag-name">sub-folder/</span>
     └─ <span class="identifier">page.php</span> <span class="comment"># The metadata and layout of the parent will also apply here</code></pre>
            </li>
            <li>
                <strong>File Priority</strong>
                <ol>
                    <li><span class="identifier">page.php</span> in the current directory</li>
                    <li><span class="identifier">layout.php</span> in the nearest parent directory</li>
                    <li>List of subdirectories (if no file is found)</li>
                </ol>

            </li>
            <li>

                    <strong>Dynamic Layouts</strong>
                    <div>Each directory can contain a <span class="identifier">layout.php</span> file with this structure :</div>
        <pre><code><span class="tag">&lt;<span class="tag-name">header</span>&gt;</span><span class="comment">&lt;!-- Navigation --&gt;</span><span class="tag">&lt;/<span class="tag-name">header</span>&gt;</span>
<span class="tag">&lt;<span class="tag-name">main</span>&gt;</span>
<span class="keymethod">&lt;?php</span> 
    <span class="keyword">echo</span> <span class="identifier">$pageContent</span>; <span class="comment">// Page contents</span> 
<span class="keymethod">?&gt;</span>
<span class="tag">&lt;/<span class="tag-name">main</span>&gt;</span>
<span class="tag">&lt;<span class="tag-name">footer</span>&gt;</span><span class="comment">&lt;!-- Footer --&gt;</span><span class="tag">&lt;/<span class="tag-name">footer</span>&gt;</span></code></pre>
                <ul>
                    <li>The variable <span class="identifier">$pageContent</span> displays the contents of the <span class="identifier">page.php</span> in the current directory and subdirectories that do not have a <span class="identifier">layout.php</span></li>
                </ul>
            </li>
            <li>

                <strong>Hierarchical Metadata System</strong>
                <div>Each directory can contain a <span class="identifier">metadata.json</span> file with this structure:</div>
                <div>
                    <pre><code>{
    <span class="identifier">"title"</span>: <span class="string">"Page Title"</span>,
    <span class="identifier">"description"</span>: <span class="string">"Meta description"</span>,
    <span class="identifier">"styles"</span>: [<span class="string">"/path/to/style.css"</span>, <span class="string">"/path/to/style-2.css"</span>]
}</code></pre>
                </div>
            
                <ul>
                    <li>Titles and descriptions are inherited from parent directories.</li>
                    <li>
                        <strong>Style Management</strong>
                        <ul>
                            <li>Stylesheets are loaded dynamically via metadata.</li>
                            <li>They are applied hierarchically (global → specific).</li>
                            <li>Styles are refreshed on each navigation.</li>
                        </ul>
                        <div>Place the <span class="identifier">metadata.json</span> file in the directory of your page that needs to apply the styles:</div>
                        <pre><code>{
    <span class="identifier">"title"</span>: <span class="string">"Styles"</span>,
    <span class="identifier">"description"</span>: <span class="string">"Page with style"</span>,
    <span class="identifier">"styles"</span>: [<span class="string">"/path/to/style.css"</span>, <span class="string">"/path/to/style-2.css"</span>]
}</code></pre>
                </li>
                </ul>
            </li>
        </ul>
        <div class="advanced-usage">
            <h4>Redirection Management</h4>
            <p>Example of redirection in a <span class="identifier">page.php</span> or <span class="identifier">layout.php</span> file:</p>
        <pre><code><span class="keymethod">&lt;?php</span>
<span class="key">if</span> (!<span class="method">user_is_logged_in</span>()) { <span class="comment">// Your redirection condition</span>
    <span class="method">spa_redirect</span>(<span class="string">'/login'</span>); <span class="comment">// Redirect to the login page</span>
}</code></pre>
    </div>
            <h4>Dynamic Loading with API</h4>
            
        <h5>JSON Responses from <span class="identifier">get-page.php</span></h5>
        <div>You can manage this response with your own logic or use <span class="key">apiClient</span> as explained below</div>
        <pre><code>{
    <span class="identifier">"content"</span>: <span class="string">"&lt;h1&gt;Welcome&lt;/h1&gt;"</span>,
    <span class="identifier">"metadata"</span>: {
    <span class="identifier">"title"</span>: <span class="string">"Styles"</span>,
    <span class="identifier">"description"</span>: <span class="string">"Page with style"</span>,
    },
    <span class="identifier">"styles"</span>: [<span class="string">"/path/to/style.css"</span>, <span class="string">"/path/to/style-2.css"</span>]
}</code></pre>
<strong>Using apiClient:</strong>
<div>Import <span class="identifier">apiClient</span> from <span class="identifier">ocho-api.js</span> :</div>
<pre><code><span class="keyword">import</span> { <span class="keymethod">apiClient</span> } <span class="keyword">from</span> <span class="string">"/app/js/ocho-api.js"</span>;</code></pre>
            <ul>
                <li>
                    <strong>Method 1:</strong>
                    <div>
                        <pre><code><span class="identifier">apiClient</span>
    .<span class="method">get</span>(<span class="string">`/api/get-page?route=/path/to/page`</span>)
    .<span class="method">then</span>((<span class="identifier">response</span>) =&gt; {
        <span class="identifier">console</span>.<span class="method">log</span>(<span class="identifier">response</span>); <span class="comment">// API response</span>
    })</code></pre>
                    </div>
                </li>
                <li>
                    <strong>Method 2:</strong>
                    <div>
                        <pre><code><span class="keyword">const</span> <span class="identifier">response</span> = <span class="keyword">await</span> <span class="identifier">apiClient</span>.<span class="method">get</span>(<span class="string">`/api/get-page.php?route=/path/to/page`</span>);

<span class="identifier">console</span>.<span class="method">log</span>(<span class="identifier">response</span>); <span class="comment">// API response</span> </code></pre>
                    </div>
                </li>
            </ul>
            <strong>Structure of the <span class="identifier">apiClient</span> response:</strong>
            <pre><code>{
    <span class="keymethod">data</span>: {
        <span class="keymethod">content</span>: <span class="string">"&lt;h1&gt;Content of your page&lt;/h1&gt;"</span>,
        <span class="keymethod">metadata</span>: {
            <span class="keymethod">title</span>: <span class="string">"Page Title"</span>,
            <span class="keymethod">description</span>: <span class="string">"Content of meta description"</span>
        },
        <span class="keymethod">styles</span>: [
            <span class="string">"/path/to/style-1.css"</span>,
            <span class="string">"/path/to/style-2.css"</span>,
        ]
    },
    <span class="keymethod">status</span>: <span class="string">200</span>,
    <span class="keymethod">statusText</span>: <span class="string">"OK"</span>,
    <span class="keymethod">headers</span>: {
        <span class="string">"X-header-1"</span>: <span class="string">"Header-1-string"</span>,
        <span class="string">"X-header-2"</span>: <span class="string">"Header-2-string"</span>,
    }
}</code></pre>
You can adapt according to your own logic or follow the instructions below: 
<ul>
    <li>
        <div>
            <div><strong>Navigation:</strong> use this function to manage dynamic navigation.</div>
    <pre>
<code><span class="keyword">async</span> <span class="keyword">function</span> <span class="method">navigate</span>(<span class="identifier">route</span>) {
    <span class="keyword">const</span> <span class="identifier">destination</span> = <span class="string">`${route}`</span>;
    <span class="keyword">const</span> <span class="identifier">response</span> = <span class="keyword">await</span> <span class="method">fetchPageContent</span>(<span class="identifier">destination</span>);
    
    
    <span class="comment">// Update the page content</span>
    <span class="identifier">document</span>.<span class="method">getElementById</span>(<span class="string">"app"</span>).<span class="keymethod">innerHTML</span> = <span class="identifier">response</span>.<span class="keymethod">content</span>;
    
    
    <span class="comment">// Update the metadata</span>
    <span class="identifier">document</span>.<span class="keymethod">title</span> = <span class="identifier">response</span>.<span class="keymethod">metadata</span>.<span class="keymethod">title</span> || <span class="string">"Title"</span>;
    <span class="keyword">const</span> <span class="identifier">metaDescription</span> = <span class="identifier">document</span>.<span class="method">querySelector</span>(<span class="string">'meta[name="description"]'</span>);
    <span class="keyword">if</span> (<span class="identifier">metaDescription</span>) {
        <span class="identifier">metaDescription</span>.<span class="keymethod">content</span> = <span class="identifier">response</span>.<span class="keymethod">metadata</span>.<span class="keymethod">description</span> || <span class="string">""</span>;
    }
    
    <span class="comment">Update the styles</span>
    <span class="keyword">const</span> <span class="identifier">exclusionList</span> = [];
    <span class="keyword">const</span> <span class="identifier">newStyles</span> = <span class="identifier">response</span>.<span class="keymethod">styles</span> ?? [];
    
    <span class="keyword">const</span> <span class="identifier">existingStyles</span> = <span class="identifier">document</span>.<span class="method">querySelectorAll</span>(<span class="string">"link[data-dynamic-css]"</span>);
    <span class="identifier">existingStyles</span>.<span class="method">forEach</span>((<span class="identifier">style</span>) =&gt; {
        <span class="keyword">const</span> <span class="identifier">sameHref</span> = <span class="identifier">newStyles</span>.<span class="method">some</span>((<span class="identifier">s</span>) =&gt; <span class="identifier">s</span> === <span class="identifier">style</span>.<span class="method">getAttribute</span>(<span class="string">"href"</span>));

        <span class="identifier">sameHref</span> &amp;&amp; <span class="identifier">exclusionList</span>.<span class="method">push</span>(<span class="identifier">style</span>.<span class="method">getAttribute</span>(<span class="string">"href"</span>));
        !<span class="identifier">sameHref</span> &amp;&amp; <span class="identifier">style</span>.<span class="method">remove</span>();
    });
    
    
    <span class="identifier">newStyles</span>.<span class="method">forEach</span>((<span class="identifier">styleUrl</span>) =&gt; {
        <span class="keyword">if</span> (!<span class="identifier">exclusionList</span>.<span class="method">includes</span>(<span class="identifier">styleUrl</span>)) {
            <span class="keyword">const</span> <span class="identifier">link</span> = <span class="identifier">document</span>.<span class="method">createElement</span>(<span class="string">"link"</span>);
            <span class="identifier">link</span>.<span class="keymethod">rel</span> = <span class="string">"stylesheet"</span>;

            <span class="identifier">link</span>.<span class="keymethod">href</span> = <span class="identifier">styleUrl</span>;

            <span class="identifier">link</span>.<span class="method">setAttribute</span>(<span class="string">"data-dynamic-css"</span>, <span class="string">"true"</span>);

            <span class="identifier">document</span>.<span class="keymethod">head</span>.<span class="method">appendChild</span>(<span class="identifier">link</span>);
        }
    });
    
    
    <span class="identifier">history</span>.<span class="method">pushState</span>({<span class="keymethod"> route </span>}, <span class="string">""</span>, <span class="identifier">destination</span>);
}</code>
    </pre>
        </div>
    </li>
    <li>
        <div><strong>Page content and redirections:</strong> use this function </div>
        
<div>
    <pre>
<code><span class="keyword">async</span> <span class="keyword">function</span> <span class="method">fetchPageContent</span>(<span class="identifier">route</span>) {
    <span class="keyword">return</span> <span class="keyword">await</span> <span class="keyword">new</span> <span class="method">Promise</span>(<span class="keyword">async</span> (<span class="identifier">resolve</span>) =&gt; {
    <span class="keyword">try</span> {
        <span class="keyword">const</span> <span class="identifier">response</span> = <span class="keyword">await</span> <span class="identifier">apiClient</span>.<span class="method">get</span>(<span class="string">`./api/get-page?route=${route}`</span>);
    
       <span class="comment">// Modify the redirection handling part:</span>
       <span class="keyword">if</span> (<span class="identifier">response</span>.<span class="keymethod">status</span> &gt;= <span class="number">300</span> &amp;&amp; <span class="identifier">response</span>.<span class="keymethod">status</span> &lt; <span class="number">400</span>) {
            <span class="keyword">const</span> <span class="identifier">location</span> = <span class="identifier">response</span>.<span class="keymethod">headers</span>[<span class="string">"x-spa-redirect"</span>] || <span class="identifier">response</span>.<span class="keymethod">data</span>.<span class="keymethod">location</span>;
    
    
        <span class="keyword">if</span> (<span class="identifier">location</span>) {
            <span class="method">navigate</span>(<span class="identifier">location</span>);
            <span class="keyword">return</span>;
        }

        <span class="identifier">console</span>.<span class="method">error</span>(<span class="string">"Redirection error"</span>);
        <span class="identifier">console</span>.<span class="method">log</span>(<span class="identifier">response</span>);
    
    
        <span class="method">resolve</span>({<span class="keymethod">
            content</span>: <span class="string">`
            &lt;h1&gt ;Error in redirection&lt;/h1&gt;
            &lt;p&gt;See the console for more details.&lt;/p&gt;
            `</span>,<span class="keymethod">
            metadata</span>: {<span class="keymethod"> title</span>: <span class="string">"Loading Error"</span> },<span class="keymethod">
            styles</span>: [],
        });
       }
        <span class="keyword">if</span> (!<span class="identifier">response</span>.<span class="keymethod">data</span>.<span class="keymethod">content</span>) {
                <span class="identifier">console</span>.<span class="method">warn</span>(<span class="string">"The response is not valid data: \n"</span>, <span class="identifier">response</span>.<span class="keymethod">data</span>);
                <span class="keyword">throw</span> <span class="keyword">new</span> <span class="method">Error</span>(<span class="string">"No valid data in the response."</span>);
        }
        <span class="method">resolve</span>(<span class="identifier">response</span>.<span class="keymethod">data</span>);
    } <span class="keyword">catch</span> (<span class="identifier">error</span>) {
       <span class="identifier">console</span>.<span class="method">error</span>(<span class="identifier">error</span>);
       <span class="comment">// Update the DOM</span>
       <span class="method">resolve</span>({<span class="keymethod">
            content</span>: <span class="string">`
            &lt;h1&gt;Page loading error&lt;/h1&gt;
            &lt;p&gt;See the console for more details.&lt;/p&gt; `</span>,
            <span class="keymethod">
            metadata</span>: {<span class="keymethod"> title</span>: <span class="string">"Loading Error"</span> },<span class="keymethod">
            styles</span>: [],
        });
    }
}</code>
    </pre>
</div>
    </li>
</ul>
        <h3>Best Practices</h3>
        <ul>
            <li>Organize styles by functionality</li>
            <li>Use metadata to improve SEO</li>
            <li>Structure layouts modularly</li>
            <li>Validate JSON files with:
                <pre><code><span class="keyword">php</span> <span class="property">-l</span> metadata.json</code></pre>
            </li>
        </ul>
    
      <p>For more information visit the full documentation</p>
      <a class="button" href="https://ochokom.github.io/ocho-spa-docs/" target="_blank" rel="noopener noreferrer">Full documentation</a>
    </div>