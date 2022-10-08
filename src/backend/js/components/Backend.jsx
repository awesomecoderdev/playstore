export const ajaxurl = awesomecoder.ajaxurl;
export const metabox = awesomecoder.metabox;
export const modified_date = awesomecoder.modified_date;
export const metaFields = metabox.fields;
export const states = metabox.states;

console.log('====================================');
console.log(modified_date);
console.log('====================================');

export default {
    awesomecoder,
    ajaxurl,
    metabox,
    metaFields,
    modified_date,
    states
}