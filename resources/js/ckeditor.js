import {
    ClassicEditor,
    Essentials,
    Paragraph,
    Bold,
    Highlight,
    HorizontalLine,
    Image, ImageInsert, ImageToolbar, ImageCaption, ImageStyle, ImageResize, LinkImage,
    Italic,
    Font,
    SourceEditing,
    CodeBlock,
    Link,
    List,
    Underline, BlockQuote, RemoveFormat,
} from 'ckeditor5';

import 'ckeditor5/translations/ru.umd.js';

document.addEventListener('DOMContentLoaded', function () {
    ClassicEditor
        .create( document.querySelector( '#editor' ), {
            licenseKey: 'GPL',
            plugins: [
                Essentials,
                Paragraph,
                Bold,
                Highlight,
                HorizontalLine,
                Image, ImageInsert, ImageToolbar, ImageCaption, ImageStyle, ImageResize, LinkImage,
                Italic,
                Underline,
                Font,
                Link,
                List,
                SourceEditing,
                CodeBlock,
                BlockQuote,
                RemoveFormat,
            ],
            toolbar: [
                'undo', 'redo',
                '|',
                'bold', 'italic', 'underline', 'removeFormat',
                '|',
                'bulletedList', 'numberedList',
                '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight',
                '|',
                'link', 'insertImage',
                '|',
                'blockQuote', 'codeBlock', 'horizontalLine', 'sourceEditing'
            ],
            image: {
                toolbar: [
                    'imageStyle:block',
                    'imageStyle:side',
                    '|',
                    'toggleImageCaption',
                    'imageTextAlternative',
                    '|',
                    'linkImage'
                ],
                insert: {
                    // If this setting is omitted, the editor defaults to 'block'.
                    // See explanation below.
                    type: 'auto'
                }
            }
        } )
        .then( editor => {
            // Заменяем Enter на Shift+Enter чтобы не создавать лишние теги <p>
            // https://github.com/ckeditor/ckeditor5/issues/1141#issuecomment-403403526
            // editor.editing.view.document.on(
            //     'enter',
            //     ( evt, data ) => {
            //         editor.execute('shiftEnter');
            //         //Cancel existing event
            //         data.preventDefault();
            //         evt.stop();
            //     }, {priority: 'high' });

            editor.model.document.on('change:data', (e) => {
                let componentData = document.querySelector('#component-data');
                let route = componentData.getAttribute('data-route');
                let attr = componentData.getAttribute('data-attr');
                let componentId = Livewire.components.getComponentsByName(route)[0].id
                Livewire.find(componentId).set(attr, editor.getData())
            })
        } )
        .catch( error => {
            console.error( error );
        } );
});
