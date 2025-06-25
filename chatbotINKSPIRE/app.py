# app.py
from flask import Flask, request, jsonify
from flask_cors import CORS
import random, json, torch

from model import NeuralNet
from nltk_utils import bag_of_words, tokenize

# --- Încarcă intenții și model ---
with open('intents.json', 'r', encoding='utf-8') as f:
    intents = json.load(f)

data = torch.load("data.pth")
all_words = data['all_words']
tags      = data['tags']

model = NeuralNet(
    data["input_size"],
    data["hidden_size"],
    data["output_size"]
)
model.load_state_dict(data["model_state"])
model.eval()

# --- Configurează Flask + CORS ---
app = Flask(__name__)
# Permite cereri din originile XAMPP (HTTP port 80)
CORS(app, origins=["http://localhost", "http://localhost:80", "http://127.0.0.1"])

@app.route("/chat", methods=["POST"])
def chat():
    req = request.get_json()
    message = req.get("message", "")

    # Preprocesare și inferență
    tokens = tokenize(message)
    X = bag_of_words(tokens, all_words).reshape(1, -1)
    X = torch.from_numpy(X).float()
    output = model(X)
    _, predicted = torch.max(output, dim=1)
    tag = tags[predicted.item()]
    prob = torch.softmax(output, dim=1)[0][predicted.item()]

    # Prag de încredere
    if prob.item() > 0.75:
        for intent in intents["intents"]:
            if intent["tag"] == tag:
                return jsonify({"reply": random.choice(intent["responses"])})
    return jsonify({"reply": "Îmi pare rău, nu am înțeles întrebarea."})

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
