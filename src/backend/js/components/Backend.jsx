export const ajaxurl = awesomecoder.ajaxurl;
export const metabox = awesomecoder.metabox;
export const modified_date = awesomecoder.modified_date;
export const metaFields = metabox.fields;
export const states = metabox.states;
export const uploads = awesomecoder.uploads;
export const post_id = awesomecoder.post_id;
export const licence_key = awesomecoder.licence_key ? awesomecoder.licence_key : "";

export const headers = {
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        "Content-type": "multipart/form-data",
        // "Keep-Alive": "timeout=5, max=1000",
    }
  };
export default {
    awesomecoder,
    ajaxurl,
    metabox,
    metaFields,
    modified_date,
    states,
    uploads,
    headers,
    licence_key
}