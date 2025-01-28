<div class="content">
    <span>GitHub Repo: <a href="https://github.com/OchoKOM/ocho-spa" class="value">https://github.com/OchoKOM/ocho-spa</a></span>
    <span>Full documentation : <a href="https://ochokom.github.io/ocho-spa-docs/" class="value">https://ochokom.github.io/ocho-spa-docs/</a></span>
    <p>This project is a single-page application (SPA) using PHP for the backend and JavaScript for the frontend. It allows for dynamically loading pages and managing routes via an API.</p>

    <h2>Project Structure</h2>
    <pre><code><span class="tag">root/</span>
├─ <span class="attribute">.htaccess</span>                <span class="comment"># Configuration for clean URLs</span>
├─ <span class="tag">api/</span>
│  ├─ <span class="attribute">get-page.php</span>          <span class="comment"># Route handler</span>
│  ├─ <span class="attribute">json-response.php</span>     <span class="comment"># Helper for JSON responses</span>
│  └─ <span class="attribute">spa-helpers.php</span>       <span class="comment"># Utility functions for the SPA</span>
├─ <span class="tag">app/</span>
│  ├─ <span class="tag">css/</span>
│  │  └─ <span class="attribute">style.css</span>          <span class="comment"># Global styles</span>
│  ├─ <span class="tag">js/</span>
│  │  ├─ <span class="attribute">app.js</span>             <span class="comment"># Main SPA logic</span>
│  │  └─ <span class="attribute">ocho-api.js</span>        <span class="comment"># API client with error handling</span>
│  └─ <span class="tag">uploads/</span>                    <span class="comment"># File storage</span>
├─ <span class="attribute">index.php</span>                <span class="comment"># Main entry point</span>
├─ <span class="tag">pages/</span>                         <span class="comment"># Page content</span>
│  ├─ <span class="tag">about/</span>
│  │  ├─ <span class="attribute">metadata.json</span>      <span class="comment"># Specific metadata</span>
│  │  └─ <span class="attribute">page.php</span>           <span class="comment"># HTML content</span>
│  ├─ <span class="tag">courses/</span>
│  │  ├─ <span class="tag">dir/</span>
│  │  │  ├─ <span class="tag">dir-1/</span>                <span class="comment"># Subdirectories</span>
│  │  │  └─ <span class="tag">dir-2/</span>
│  │  └─ <span class="attribute">page.php</span>
│  ├─ <span class="attribute">layout.php</span>            <span class="comment"># Main layout</span>
│  ├─ <span class="attribute">page.php</span>              <span class="comment"># Default layout</span>
│  └─ <span class="comment">**other pages here**</span>    <span class="comment"># Add your pages here</span>
└─ <span class="attribute">README.md</span>                <span class="comment"># Documentation</span></code></pre>

    <h2>File Details</h2>
    <ul>
        <li><strong><span class="attribute">.htaccess</span></strong> : Configures Apache to redirect requests to <span class="attribute">index.php</span> and handle clean URLs.</li>
        <li><strong><span class="attribute">api/get-page.php</span></strong> : PHP script to resolve routes and return the HTML content of pages.</li>
        <li><strong><span class="attribute">api/json-response.php</span></strong> : PHP function to send responses in JSON format.</li>
        <li><strong><span class="attribute">api/spa-helpers.php</span></strong> : Utility functions for handling redirects and other SPA features.</li>
        <li><strong><span class="attribute">app/css/style.css</span></strong> : Main stylesheet for the application.</li>
        <li><strong><span class="attribute">app/js/app.js</span></strong> : JavaScript script to handle navigation and dynamic page loading.</li>
        <li><strong><span class="attribute">app/js/ocho-api.js</span></strong> : JavaScript class to make HTTP requests to the API.</li>
        <li><strong><span class="attribute">index.php</span></strong> : Main entry point of the application, serving as the layout for the SPA.</li>
        <li><strong><span class="tag">pages/</span></strong> : Directory containing the application's pages, each with its own directory.</li>
    </ul>

    <h2>Features</h2>
    <ul>
        <li><strong>Dynamic Routing</strong> : Dynamic route resolution via <span class="attribute">api/get-page.php</span>, with page content loading in JavaScript.</li>
        <li><strong>History Navigation</strong> : Use of the History API to manage navigation with the browser's back/forward buttons.</li>
        <li><strong>JSON Responses</strong> : APIs return structured responses in JSON format.</li>
        <li><strong>Redirect Management</strong> : Integrated redirect system for smooth navigation without page reload.</li>
    </ul>

    <h2>Installation</h2>
    <ol>
        <li>Clone the repository:
            <pre><code><span class="tag">git</span> <span class="tag-name">clone</span> <span class="value">https://github.com/OchoKOM/ocho-spa</span></code></pre>
        </li>
        <li>Place the project in your web server (e.g., <span class="value">htdocs</span> for XAMPP or <span class="value">www</span> for WAMP).</li>
        <li>Ensure the Apache module <span class="attribute">mod_rewrite</span> is enabled for <span class="attribute">.htaccess</span> to work.</li>
        <li>Configure a VirtualHost (recommended) to point directly to the project directory.</li>
        <li>Access the application via your browser at <span class="value">http://localhost/</span> or your VirtualHost address.</li>
    </ol>
    <div class="note">
        <strong>Important Note:</strong> The application requires a root configuration (e.g., <span class="value">http://localhost</span>).<br>
        Subdirectories may cause routing issues.
    </div>
    <h2>Usage</h2>
    <ul>
        <li><strong>Pages</strong> : Add folders in <span class="tag">pages/</span> for each application page. Insert a <span class="attribute">page.php</span> or <span class="attribute">layout.php</span> file for HTML content.</li>
        <li><strong>Navigation</strong> : Click on links to navigate. Pages will load dynamically without a full reload.</li>
        <li><strong>API</strong> : API requests are handled by <span class="attribute">OchoClient</span> in <span class="attribute">ocho-api.js</span>. For more information visit the repo <a href="https://github.com/OchoKOM/xhr" class="value">https://github.com/OchoKOM/xhr</a></li>
        <li><strong>Redirects</strong> : Use the <span class="tag-name">spa_redirect()</span> function to redirect to another route.</li>
    </ul>

    <h2>API Usage Example</h2>
    <pre><code><span class="key">import</span> { <span class="tag">apiClient</span> } <span class="key">from</span> <span class="value">"./app/js/ocho-api.js"</span>;

<span class="attribute">apiClient</span>.<span class="method">get</span>(<span class="value">"api/get-page.php?route=about"</span>)
  .<span class="method">then</span>((<span class="attribute">response</span>) => {
    <span class="attribute">console</span>.<span class="method">log</span>(<span class="attribute">response</span>.<span class="tag">data</span>);
  })
  .<span class="method">catch</span>((<span class="attribute">error</span>) => {
    <span class="attribute">console</span>.<span class="method">error</span>(<span class="attribute">error</span>);
  });</code></pre>

    <h2>Key Features</h2>
    <h3>Hierarchical Metadata System</h3>
    <p>Each directory can contain a <span class="attribute">metadata.json</span> file:</p>
    <pre><code>{
  <span class="attribute">"title"</span>: <span class="value">"About"</span>,
  <span class="attribute">"description"</span>: <span class="value">"Company presentation page"</span>,
  <span class="attribute">"styles"</span>: [<span class="value">"/app/css/about.css"</span>]
}</code></pre>

    <ul>
        <li>Titles and descriptions are inherited from parent directories.</li>
        <li>Styles are cumulative from parent to child.</li>
    </ul>

    <h3>Dynamic Layouts</h3>
    <p>Example of <span class="attribute">layout.php</span> structure:</p>
    <pre><code><span class="tag">&lt;<span class="tag-name">header</span>&gt;</span><span class="comment">&lt;!-- Navigation --&gt;</span><span class="tag">&lt;/<span class="tag-name">header</span>&gt;</span>
<span class="tag">&lt;<span class="tag-name">main</span>&gt;</span>
<span class="key">&lt;?php</span> 
  <span class="tag">echo</span> <span class="attribute">$pageContent</span>; <span class="comment">/* The $pageContent variable displays the content of page.php from the current directory and subdirectories that do not have a layout.php */</span> 
<span class="key">?&gt;</span>
<span class="tag">&lt;/<span class="tag-name">main</span>&gt;</span>
<span class="tag">&lt;<span class="tag-name">footer</span>&gt;</span><span class="comment">&lt;!-- Footer --&gt;</span><span class="tag">&lt;/<span class="tag-name">footer</span>&gt;</span></code></pre>
    <h3>Style Management</h3>
    <ul>
        <li>Stylesheets are dynamically loaded via metadata.</li>
        <li>They are applied hierarchically (global → specific).</li>
        <li>Styles are refreshed on each navigation.</li>
    </ul>
    <h4>Example of Metadata with Styles</h4>
    <p>Place the <span class="attribute">metadata.json</span> file in your page's directory to apply styles:</p>
    <pre><code>{
  <span class="attribute">"title"</span>: <span class="value">"Styles"</span>,
  <span class="attribute">"description"</span>: <span class="value">"Page with styles"</span>,
  <span class="attribute">"styles"</span>: [<span class="value">"/path/to/style.css"</span>, <span class="value">"/path/to/style-2.css"</span>]
}</code></pre>
    <h3>Integrated API</h3>
    <p>Example of JSON response from the API managed by <span class="key">apiClient</span>:</p>
    <pre><code>{
  <span class="attribute">"content"</span>: <span class="value">"&lt;h1&gt;Welcome&lt;/h1&gt;"</span>,
  <span class="attribute">"metadata"</span>: {
  <span class="attribute">"title"</span>: <span class="value">"Styles"</span>,
  <span class="attribute">"description"</span>: <span class="value">"Page with styles"</span>,
  },
  <span class="attribute">"styles"</span>: [<span class="value">"/path/to/style.css"</span>, <span class="value">"/path/to/style-2.css"</span>]
}</code></pre>
    <ul>
        <li><strong>Final Content (converted by apiClient)</strong> : 
        <p>You can handle this response according to your own logic.</p>
        <pre><code>{
  <span class="attribute">data</span>: {
    <span class="attribute">content</span>: <span class="value">"&lt;h1&gt;Welcome&lt;/h1&gt;"</span>,
    <span class="attribute">metadata</span>: {
        <span class="attribute">title</span>: <span class="value">"Home"</span>,
        <span class="attribute">description</span>: <span class="value">"Demo site"</span>
    },
    <span class="attribute">styles</span>: [
          <span class="value">"/app/css/style.css"</span>
    ],
  },
  <span class="attribute">status</span>: <span class="value">200</span>,
  <span class="attribute">statusText</span>: <span class="value">"OK"</span>,
  <span class="attribute">headers</span>: {
    <span class="attribute">"header-1"</span>: <span class="value">"Header-1-value"</span>,
  }
}</code></pre>
        </li>
    </ul>
    <h3>Redirect Management</h3>
    <p>Example of redirection in a <span class="attribute">page.php</span> or <span class="attribute">layout.php</span> file:</p>
    <pre><code><span class="tag">&lt;?php</span>
<span class="key">if</span> (!<span class="method">user_is_logged_in</span>()) {
  <span class="method">spa_redirect</span>(<span class="value">'/login'</span>);
}</code></pre>


<h3>Dynamic Loading with Metadata</h3>
    <p>Example of page loading script:</p>
    <pre><code><span class="key">async</span> <span class="key">function</span> <span class="method">fetchPageContent</span>(<span class="attribute">route</span>) {
  <span class="key">return</span> <span class="key">await</span> <span class="key">new</span> <span class="method">Promise</span>(<span class="key">async</span> (<span class="attribute">resolve</span>, <span class="attribute">reject</span>) => {
    <span class="key">try</span> {
        <span class="key">const</span> <span class="attribute">response</span> = <span class="key">await</span> <span class="attribute">apiClient</span>.<span class="method">get</span>(<span class="value">`/api/get-page.php?route=<span class="attribute">${route}</span>`</span>);
        
        <span class="comment">// Modify the redirection handling part:</span>
        <span class="key">if</span> (<span class="attribute">response</span>.<span class="tag">status</span> >= <span class="value">300</span> && <span class="attribute">response</span>.<span class="tag">status</span> < <span class="value">400</span>) {
            <span class="key">const</span> <span class="attribute">location</span> = <span class="attribute">response</span>.<span class="tag">headers</span>[<span class="value">'x-spa-redirect'</span>] || <span class="attribute">response</span>.<span class="tag">data</span>.<span class="tag">location</span>;
            
            <span class="key">if</span> (<span class="attribute">location</span>) {
            <span class="method">navigate</span>(<span class="attribute">location</span>);
            <span class="key">return</span>;
            }
            <span class="attribute">console</span>.<span class="method">error</span>(<span class="value">"Redirection error"</span>);
            <span class="attribute">console</span>.<span class="method">log</span>(<span class="attribute">response</span>);
            
            <span class="method">resolve</span>({
            <span class="tag">content</span>: <span class="value">`
                            &lt;h1&gt;Redirection Error&lt;/h1&gt;
                            &lt;p&gt;Check the console for more details.&lt;/p&gt;
                          `</span>,
            <span class="tag">metadata</span>: { <span class="tag">title</span>: <span class="value">"Loading Error"</span> },
            <span class="tag">styles</span>: [],
            });
        }
        <span class="key">if</span> (!<span class="attribute">response</span>.<span class="tag">data</span>.<span class="tag">content</span>) {
            <span class="attribute">console</span>.<span class="method">warn</span>(<span class="value">"The response is not valid data: \n"</span>, <span class="attribute">response</span>.<span class="tag">data</span>);
            <span class="key">throw</span> <span class="key">new</span> <span class="method">Error</span>(<span class="value">"No valid data in the response."</span>);
        }
        <span class="method">resolve</span>(<span class="attribute">response</span>.<span class="tag">data</span>);
    } <span class="key">catch</span> (<span class="attribute">error</span>) {
        <span class="attribute">console</span>.<span class="method">error</span>(<span class="attribute">error</span>);
        <span class="comment">// Update the DOM</span>
        <span class="method">resolve</span>({
            <span class="tag">content</span>: <span class="value">`
                          &lt;h1&gt;Page Loading Error&lt;/h1&gt;
                          &lt;p&gt;Check the console for more details.&lt;/p&gt;
                          `</span>,
            <span class="tag">metadata</span>: { <span class="tag">title</span>: <span class="value">"Loading Error"</span> },
            <span class="tag">styles</span>: [],
        });
    }
  });
}</code></pre>

    
<h2>Advanced Configuration</h2>
    <pre><code><span class="attribute">RewriteEngine</span> <span class="value">On</span>

<span class="comment"># Exclusion of assets and API</span>
<span class="attribute">RewriteCond</span> <span class="value">%{REQUEST_URI}</span> !^/api/ [NC]
<span class="attribute">RewriteCond</span> <span class="value">%{REQUEST_URI}</span> !^/app/ [NC]
<span class="attribute">RewriteCond</span> <span class="value">%{REQUEST_FILENAME}</span> !-f
<span class="attribute">RewriteCond</span> <span class="value">%{REQUEST_FILENAME}</span> !-d
<span class="attribute">RewriteRule</span> ^ index.php [L]</code></pre>
    <h3>File Priority</h3>
    <ol>
        <li><span class="attribute">page.php</span> in the current directory</li>
        <li><span class="attribute">layout.php</span> in the nearest parent directory</li>
        <li>List of subdirectories (if no file is found)</li>
    </ol>

    <h2>Best Practices</h2>
    <ul>
        <li>Organize styles by functionality</li>
        <li>Use metadata to improve SEO</li>
        <li>Structure layouts modularly</li>
        <li>Validate JSON files with:
            <pre><code><span class="tag">php</span> -l <span class="attribute">metadata.json</span></code></pre>
        </li>
    </ul>

    <h2>Contribute</h2>
    <p>Contributions are welcome. Open a <span class="attribute">pull request</span> or an <span class="attribute">issue</span> to discuss changes.</p>

    <h2>License</h2>
    <p>This project is licensed under the <span class="attribute">MIT</span> license. See the <span class="attribute">LICENSE</span> file for details.</p>
</div>