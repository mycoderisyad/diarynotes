<script>
    (() => {
        const form = document.querySelector('[data-note-editor-form]');
        const editor = document.getElementById('note-editor');
        const contentInput = document.getElementById('note-content-input');
        const hiddenUploads = document.getElementById('editor-hidden-uploads');
        const imagePicker = document.getElementById('editor-image-picker');
        const audioPicker = document.getElementById('editor-audio-picker');
        const recorderStatus = document.querySelector('[data-recorder-status]');
        const recordButton = document.querySelector('[data-record-button]');

        if (!form || !editor || !contentInput) {
            return;
        }

        let mediaRecorder = null;
        let recorderChunks = [];
        let recorderStream = null;
        let lastSelectionRange = null;

        const ensureEditorHasContent = () => {
            if (!editor.textContent.trim() && !editor.querySelector('.editor-embed')) {
                editor.innerHTML = '<p><br></p>';
            }
        };

        const saveSelection = () => {
            const selection = window.getSelection();
            if (!selection || !selection.rangeCount) {
                return;
            }

            const range = selection.getRangeAt(0);
            if (editor.contains(range.commonAncestorContainer)) {
                lastSelectionRange = range.cloneRange();
            }
        };

        const restoreSelection = () => {
            const selection = window.getSelection();

            if (lastSelectionRange) {
                selection.removeAllRanges();
                selection.addRange(lastSelectionRange);
                return lastSelectionRange.cloneRange();
            }

            const range = document.createRange();
            range.selectNodeContents(editor);
            range.collapse(false);
            selection.removeAllRanges();
            selection.addRange(range);
            lastSelectionRange = range.cloneRange();

            return range;
        };

        const placeCaretAfter = (node) => {
            const range = document.createRange();
            range.setStartAfter(node);
            range.collapse(true);

            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            lastSelectionRange = range.cloneRange();
        };

        const createHiddenFileInput = (groupName, key, file) => {
            const input = document.createElement('input');
            input.type = 'file';
            input.name = `${groupName}[${key}]`;
            input.hidden = true;

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            hiddenUploads.appendChild(input);
        };

        const createEmbedNode = ({ kind, key = '', previewUrl, name, mime = '', mediaPath = '' }) => {
            const embed = document.createElement('span');
            embed.className = `editor-embed editor-embed-${kind}`;
            embed.contentEditable = 'false';
            embed.dataset.kind = kind;

            if (key) {
                embed.dataset.uploadKey = key;
            }

            if (mediaPath) {
                embed.dataset.mediaPath = mediaPath;
            }

            embed.dataset.mediaName = name || '';
            embed.dataset.mediaMime = mime || '';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'editor-embed-remove';
            removeButton.setAttribute('aria-label', `Remove ${kind}`);
            removeButton.textContent = 'Remove';
            embed.appendChild(removeButton);

            const preview = document.createElement('span');
            preview.className = 'editor-embed-preview';

            if (kind === 'image') {
                const image = document.createElement('img');
                image.src = previewUrl;
                image.alt = name || 'Embedded image';
                preview.appendChild(image);
            } else {
                const audio = document.createElement('audio');
                audio.controls = true;
                audio.preload = 'metadata';
                audio.className = 'note-audio-player';
                const source = document.createElement('source');
                source.src = previewUrl;
                source.type = mime || 'audio/mpeg';
                audio.appendChild(source);
                preview.appendChild(audio);
            }

            const label = document.createElement('span');
            label.className = 'editor-embed-label';
            label.textContent = name || (kind === 'image' ? 'Image' : 'Audio');

            embed.appendChild(preview);
            embed.appendChild(label);

            return embed;
        };

        const insertEmbedAtCaret = (embed) => {
            editor.focus();
            const range = restoreSelection();
            const spacer = document.createTextNode('\u00A0');
            const fragment = document.createDocumentFragment();
            fragment.appendChild(embed);
            fragment.appendChild(spacer);

            range.deleteContents();
            range.insertNode(fragment);
            placeCaretAfter(spacer);
        };

        const handleSelectedFile = (kind, file) => {
            if (!file) {
                return;
            }

            const key = `${kind}-${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
            createHiddenFileInput(kind === 'image' ? 'embedded_images' : 'embedded_audios', key, file);

            insertEmbedAtCaret(createEmbedNode({
                kind,
                key,
                previewUrl: URL.createObjectURL(file),
                name: file.name,
                mime: file.type,
            }));
        };

        const serializeEditor = () => {
            const clone = editor.cloneNode(true);

            clone.querySelectorAll('.editor-embed-remove').forEach((button) => button.remove());
            clone.querySelectorAll('.editor-embed').forEach((embed) => {
                const placeholder = document.createElement('span');
                placeholder.className = 'note-embed';
                placeholder.dataset.kind = embed.dataset.kind || '';

                if (embed.dataset.uploadKey) {
                    placeholder.dataset.uploadKey = embed.dataset.uploadKey;
                }

                if (embed.dataset.mediaPath) {
                    placeholder.dataset.mediaPath = embed.dataset.mediaPath;
                }

                if (embed.dataset.mediaName) {
                    placeholder.dataset.mediaName = embed.dataset.mediaName;
                }

                if (embed.dataset.mediaMime) {
                    placeholder.dataset.mediaMime = embed.dataset.mediaMime;
                }

                embed.replaceWith(placeholder);
            });

            const html = clone.innerHTML
                .replace(/<p><\/p>/gi, '<p><br></p>')
                .replace(/&nbsp;/gi, ' ')
                .trim();

            if (!clone.textContent.trim() && !clone.querySelector('.note-embed')) {
                return '';
            }

            return html;
        };

        editor.addEventListener('paste', (event) => {
            event.preventDefault();
            const text = event.clipboardData.getData('text/plain');
            document.execCommand('insertText', false, text);
        });

        editor.addEventListener('input', ensureEditorHasContent);
        editor.addEventListener('keyup', saveSelection);
        editor.addEventListener('mouseup', saveSelection);
        editor.addEventListener('focus', saveSelection);
        document.addEventListener('selectionchange', saveSelection);

        editor.addEventListener('click', (event) => {
            const removeButton = event.target.closest('.editor-embed-remove');
            if (!removeButton) {
                return;
            }

            const embed = removeButton.closest('.editor-embed');
            if (!embed) {
                return;
            }

            const placeholder = document.createTextNode(' ');
            embed.replaceWith(placeholder);
            placeCaretAfter(placeholder);
            ensureEditorHasContent();
        });

        document.querySelectorAll('[data-editor-action]').forEach((button) => {
            button.addEventListener('mousedown', (event) => event.preventDefault());
            button.addEventListener('click', async () => {
                saveSelection();
                const action = button.dataset.editorAction;

                if (action === 'image') {
                    imagePicker.click();
                    return;
                }

                if (action === 'audio') {
                    audioPicker.click();
                    return;
                }

                if (action === 'record') {
                    if (!window.MediaRecorder || !navigator.mediaDevices?.getUserMedia) {
                        recorderStatus.textContent = 'Audio recording is not supported in this browser.';
                        return;
                    }

                    if (mediaRecorder && mediaRecorder.state === 'recording') {
                        mediaRecorder.stop();
                        recordButton.textContent = 'Record Audio';
                        recorderStatus.textContent = 'Processing recording...';
                        return;
                    }

                    try {
                        recorderStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        mediaRecorder = new MediaRecorder(recorderStream);
                        recorderChunks = [];

                        mediaRecorder.addEventListener('dataavailable', (recordEvent) => {
                            if (recordEvent.data.size > 0) {
                                recorderChunks.push(recordEvent.data);
                            }
                        });

                        mediaRecorder.addEventListener('stop', () => {
                            const mime = mediaRecorder.mimeType || 'audio/webm';
                            const extension = mime.includes('ogg') ? 'ogg' : (mime.includes('mp4') ? 'm4a' : 'webm');
                            const blob = new Blob(recorderChunks, { type: mime });
                            const file = new File([blob], `recording-${Date.now()}.${extension}`, { type: mime });

                            handleSelectedFile('audio', file);
                            recorderStatus.textContent = 'Recording inserted into the note.';
                            recordButton.textContent = 'Record Audio';

                            if (recorderStream) {
                                recorderStream.getTracks().forEach((track) => track.stop());
                            }

                            mediaRecorder = null;
                            recorderChunks = [];
                        });

                        mediaRecorder.start();
                        recordButton.textContent = 'Stop Recording';
                        recorderStatus.textContent = 'Recording audio...';
                    } catch (error) {
                        recorderStatus.textContent = 'Microphone access was denied.';
                    }
                }
            });
        });

        imagePicker.addEventListener('change', () => {
            const [file] = imagePicker.files || [];
            handleSelectedFile('image', file);
            imagePicker.value = '';
        });

        audioPicker.addEventListener('change', () => {
            const [file] = audioPicker.files || [];
            handleSelectedFile('audio', file);
            audioPicker.value = '';
        });

        form.addEventListener('submit', () => {
            contentInput.value = serializeEditor();
        });

        ensureEditorHasContent();
        saveSelection();
    })();
</script>
