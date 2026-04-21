    </main>

    <footer class="site-footer">
        <div>Developed By: Raymond Russelburg / AI</div>
    </footer>
</div>

<script>
function copyTextToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
        return navigator.clipboard.writeText(text);
    }

    return new Promise(function(resolve, reject) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);
            if (successful) {
                resolve();
            } else {
                reject(new Error('Copy command failed'));
            }
        } catch (err) {
            document.body.removeChild(textArea);
            reject(err);
        }
    });
}
</script>
</body>
</html>
