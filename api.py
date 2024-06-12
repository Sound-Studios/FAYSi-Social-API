import requests

def upload_file(file_path, title):
    url = 'http://social.faysi.de/upload.php' 
    files = {'file': open(file_path, 'rb')}
    data = {'title': title}

    response = requests.post(url, files=files, data=data)

    if response.status_code == 200:
        print("File uploaded successfully.")
    else:
        print("Failed to upload file.")

# Beispielaufruf
upload_file('/path/to/your/file.jpg', 'Example Title')
