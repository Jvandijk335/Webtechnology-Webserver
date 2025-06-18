# 🕸️ Unity Network Connections – High Score API Poster

This Unity C# script (`Network_Connections.cs`) demonstrates how to send a JSON-formatted HTTP POST request from a Unity game to a backend server to store high scores. It uses Unity's built-in `UnityWebRequest` system and supports JSON serialization of player data.

Inspired by:  
👉 [Matthew J Spencer’s Gist on UnityWebRequest](https://gist.github.com/Matthew-J-Spencer/8da232ef3d3ce81a0f4a2cb087eb740b)

---

## 🧠 Purpose

The script allows a Unity game to **send a player's name and score to a remote API endpoint** (such as `http://localhost:8080/api/highscores`) using a POST request. This is commonly used for online leaderboards, player statistics, or multiplayer interactions.

---

## 📦 Features

- Accepts a player name input through a UI field (TMP Input Field)
- Sends score data as JSON to a web server
- Handles POST requests using `UnityWebRequest`
- Includes simple enum-based request type handling
- Handles deserialization of the server response

---

## 📁 Dependencies

Ensure your Unity project includes:

- **TextMeshPro** (`TMP_InputField`)
- A score manager singleton (`Score_Manager.instance.GetScore()`)
- Internet access permissions (if deploying to mobile)

---

## 📜 Script Breakdown

### 🔧 Serialized Field

```csharp
[SerializeField] TMP_InputField inputField;
```
> This links a TextMeshPro Input Field in the Unity Editor for capturing the username.

---

### 🚀 `Sendrequest()` Method

```csharp
public void Sendrequest() {
    StartCoroutine(Postrequest());
}
```
> Public method to begin the POST process (can be called from a button click).

---

### 🌐 `Postrequest()` Coroutine

```csharp
var dataToPost = new PostData() { username = inputField.text, score = Score_Manager.instance.GetScore() };
```
> Creates a `PostData` object containing the player's name and score.

```csharp
var postRequest = CreateRequest("http://localhost:8080/api/highscores", RequestType.POST, dataToPost);
yield return postRequest.SendWebRequest();
```
> Sends the data as a POST request and waits for a response.

```csharp
var deserializedPostData = JsonUtility.FromJson<PostResult>(postRequest.downloadHandler.text);
```
> Converts the JSON response into a `PostResult` object.

---

### 🏗️ `CreateRequest()` Method

```csharp
var request = new UnityWebRequest(path, type.ToString());
```
> Initializes a request with a specific HTTP method.

```csharp
var bodyRaw = Encoding.UTF8.GetBytes(JsonUtility.ToJson(data));
request.uploadHandler = new UploadHandlerRaw(bodyRaw);
```
> Converts the C# object to JSON and attaches it to the request body.

```csharp
request.downloadHandler = new DownloadHandlerBuffer();
request.SetRequestHeader("Content-Type", "application/json");
```
> Sets headers and prepares to receive the server's response.

---

### 📊 Data Models

```csharp
public class PostData {
    public string username;
    public int score;
}
```

> Structure of the data being sent to the server.

```csharp
public class PostResult {
    public string success { get; set; }
}
```
> Expected structure of the response from the server (e.g., `{ "success": "true" }`).

---

## ✅ How to Use

1. Attach this script to any GameObject in your Unity scene.
2. Assign a `TMP_InputField` from your UI to the `inputField` field in the Inspector.
3. Hook the `Sendrequest()` method to a UI button’s `OnClick()` event.
4. Run the game, enter a name, and click the button to send data.

---

## 🧪 Sample Server Response

This script expects a response like:
```json
{ "success": "true" }
```

---

## 🚨 Notes

- Make sure the server is running at the specified URL (`http://localhost:8080/api/highscores`) or update it accordingly.
- When building for WebGL or mobile, check cross-origin and security settings.

---

## 📌 License

This implementation is free to use and modify. Original inspiration credited to [Matthew J Spencer](https://gist.github.com/Matthew-J-Spencer/8da232ef3d3ce81a0f4a2cb087eb740b).

