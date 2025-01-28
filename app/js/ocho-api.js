export default class OchoClient {
  constructor(baseUrl, defaultOptions = {}) {
    if (!baseUrl || typeof baseUrl !== "string") {
      throw new Error("baseUrl doit être une chaîne de caractères valide.");
    }

    this.baseUrl = baseUrl.replace(/\/$/, ""); // Supprime les "/" en fin d'URL
    this.defaultOptions = {
      headers: {},
      body: null,
      throwHttpErrors: true,
      timeout: 0, // Pas de timeout par défaut
      ...defaultOptions, // Permet à l'utilisateur de personnaliser les options par défaut
    };
  }

  sendRequest(method, endpoint, options = {}, onProgress = null) {
    if (
      !method ||
      !["GET", "POST", "PUT", "PATCH", "DELETE"].includes(method.toUpperCase())
    ) {
      throw new Error("Méthode HTTP invalide.");
    }

    if (!endpoint || typeof endpoint !== "string") {
      throw new Error("endpoint doit être une chaîne de caractères valide.");
    }

    const mergedOptions = {
      ...this.defaultOptions,
      ...options,
      headers: { ...this.defaultOptions.headers, ...options.headers }, // Fusion des en-têtes
    };

    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();
      xhr.open(
        method.toUpperCase(),
        `${this.baseUrl}/${endpoint.replace(/^\//, "")}`
      );

      // Application des en-têtes HTTP
      Object.entries(mergedOptions.headers).forEach(([key, value]) => {
        xhr.setRequestHeader(key, value);
      });

      // Gestion du timeout
      if (mergedOptions.timeout > 0) {
        xhr.timeout = mergedOptions.timeout;
      }

      // Gestion de la progression
      if (typeof onProgress === "function") {
        xhr.upload.onprogress = (event) => {
          if (event.lengthComputable) {
            const progress = (event.loaded / event.total) * 100;
            onProgress(progress, event);
          } else {
            onProgress(null, event); // Taille inconnue
          }
        };
      }

      // Nouvelle fonction de parsing des en-têtes
      const parseHeaders = (headersString) => {
        const headers = {};
        if (headersString) {
          headersString
            .trim()
            .split("\r\n")
            .forEach((line) => {
              const [name, ...valueParts] = line.split(": ");
              const value = valueParts.join(": ");
              if (name && value) {
                headers[name.toLowerCase()] = value;
              }
            });
        }
        return headers;
      };

      xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          const headers = parseHeaders(xhr.getAllResponseHeaders());
          if (xhr.status >= 200 && xhr.status < 300) {
            try {
              const data = JSON.parse(xhr.response);
              const status = xhr.status;
              const statusText = xhr.statusText;
              resolve({
                data,
                status,
                statusText,
                headers,
              });
            } catch (error) {
              const data = xhr.response;
              const status = xhr.status;
              const statusText = xhr.statusText;
              if (mergedOptions.throwHttpErrors) {
                console.log({
                  data,
                  status,
                  statusText,
                  headers,
                });
                reject(new Error("Erreur de parsing JSON"));
              } else {
                console.error(error);
                resolve({
                  data,
                  status,
                  statusText,
                  headers,
                }); // Retourne la réponse brute
              }
            }
          } else if (xhr.status >= 300 && xhr.status < 400) {
            const data = JSON.parse(xhr.response);
            const status = xhr.status;
            const statusText = xhr.statusText;
            if (mergedOptions.throwHttpErrors) {
              console.warn(
                `Code HTTP ${xhr.status}, Redirection`
              );
            }
            resolve({
              data,
              status,
              statusText,
              headers,
            });
          } else {
            const data = JSON.parse(xhr.response);
            const status = xhr.status;
            const statusText = xhr.statusText;
            if (mergedOptions.throwHttpErrors) {
              console.log({
                data,
                status,
                statusText,
                headers,
              });
              reject(new Error(`Erreur HTTP ${xhr.status}: ${xhr.statusText}`));
            } else {
              console.error(`Erreur HTTP ${xhr.status}: ${xhr.statusText}`);
              resolve({
                data,
                status,
                statusText,
                headers,
              }); // Retourne la réponse brute
            }
          }
        }
      };

      xhr.onerror = () => {
        reject(new Error("Erreur réseau"));
      };

      xhr.ontimeout = () => {
        reject(new Error(`Requête expirée après ${mergedOptions.timeout} ms`));
      };

      // Préparation et envoi du corps de la requête
      if (mergedOptions.body) {
        if (mergedOptions.body instanceof FormData) {
          xhr.send(mergedOptions.body);
        } else if (typeof mergedOptions.body === "object") {
          const formData = new FormData();
          Object.entries(mergedOptions.body).forEach(([key, value]) => {
            formData.append(key, value);
          });
          xhr.send(formData);
        } else if (typeof mergedOptions.body === "string") {
          xhr.setRequestHeader("Content-Type", "application/json");
          xhr.send(mergedOptions.body);
        } else {
          xhr.send(mergedOptions.body);
        }
      } else {
        xhr.send();
      }
    });
  }

  request(method, endpoint, options = {}, onProgress = null) {
    return this.sendRequest(method, endpoint, options, onProgress);
  }

  get(endpoint, options = {}, onProgress) {
    return this.request("GET", endpoint, options, onProgress);
  }

  post(endpoint, options = {}, onProgress) {
    return this.request("POST", endpoint, options, onProgress);
  }

  put(endpoint, options = {}, onProgress) {
    return this.request("PUT", endpoint, options, onProgress);
  }

  patch(endpoint, options = {}, onProgress) {
    return this.request("PATCH", endpoint, options, onProgress);
  }

  delete(endpoint, options = {}, onProgress) {
    return this.request("DELETE", endpoint, options, onProgress);
  }
}

// Exemple d'utilisation
// todo: On appele la classe avec notre url de base
export const apiClient = new OchoClient("/", {
  headers: {
    Authorization: "Bearer your-token",
  },
  throwHttpErrors: false,
});
