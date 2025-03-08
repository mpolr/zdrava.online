import {
    ClassicEditor,
    Essentials,
    Paragraph,
    Bold,
    Italic,
    Font,
    SourceEditing,
    CodeBlock,
    Link,
    Underline, BlockQuote, RemoveFormat,
} from 'ckeditor5';

import 'ckeditor5/translations/ru.umd.js'

document.addEventListener('DOMContentLoaded', function () {
    ClassicEditor
        .create( document.querySelector( '#editor' ), {
            licenseKey: 'GPL',
            plugins: [
                Essentials,
                Paragraph,
                Bold,
                Italic,
                Underline,
                Font,
                Link,
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
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor',
                '|',
                'link',
                '|',
                'blockQuote', 'codeBlock', 'sourceEditing'
            ]
        } )
        .then( editor => {
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
