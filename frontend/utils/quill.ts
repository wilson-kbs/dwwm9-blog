import Quill from 'quill';

import Toolbar from 'quill/modules/toolbar';
import Snow from 'quill/themes/snow';

import Bold from 'quill/formats/bold';
import Italic from 'quill/formats/italic';
import Header from 'quill/formats/header';

import "../../node_modules/quill/dist/quill.bubble.css"
import "../../node_modules/quill/dist/quill.core.css"
import "../../node_modules/quill/dist/quill.snow.css"

// import { ImageResize } from 'quill-image-resize-module';

// Quill.register('modules/imageResize', ImageResize);

// Quill.register({
//   'modules/toolbar': Toolbar,
//   'themes/snow': Snow,
//   'formats/bold': Bold,
//   'formats/italic': Italic,
//   'formats/header': Header
// });


export default Quill;