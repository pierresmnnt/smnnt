/**
 * @param {URL} url
 */
export default async function fetchUrl(url) {
  const response = await fetch(url, {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  });
  if (response.status >= 200 && response.status < 300) {
    return await response.json();
  } else {
    console.error(response);
  }
}
