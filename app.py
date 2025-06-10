from flask import Flask, request, jsonify, render_template
import random

app = Flask(__name__)

# Simple ML-like chatbot (rule-based for now)
responses = {
    "hello": [
        "Hi there! Ready for an adventure?",
        "Hello! Looking for your next trek?",
        "Hey traveler! How can I assist you today?"
    ],
    "hii": [
        "Hi there! Ready for an adventure?",
        "Hello! Looking for your next trek?",
        "Hey traveler! How can I assist you today?"
    ],
    "hai": [
        "Hi there! Ready for an adventure?",
        "Hello! Looking for your next trek?",
        "Hey traveler! How can I assist you today?"
    ],
    "book": [
        "Sure, where would you like to go?",
        "Booking? I can help with that. Solo or group trek?",
        "Great! Let’s get your trip planned. What's your destination?"
    ],
    "bye": [
        "Goodbye! Safe travels!",
        "See you soon on your next adventure!",
        "Have a great journey! Don't forget to share your memories."
    ],
    "destination": [
        "We have amazing treks to the Himalayas, Sahyadris, and Western Ghats!",
        "Looking for mountains, beaches, or forests?",
        "Tell me your adventure level – beginner or expert – and I’ll suggest places."
    ],
    "weather": [
        "Let me check the weather for your chosen destination.",
        "It’s always good to pack for unexpected rain in the hills!",
        "The forecast looks perfect for trekking – cool and dry!"
    ],
    "packing": [
        "Don't forget essentials like water bottles, warm clothing, and a first-aid kit!",
        "Need a packing list? I’ve got you covered.",
        "Pack light but smart – layers, snacks, and good shoes are key."
    ],
    "safety": [
        "Always travel with a buddy or let someone know your route!",
        "Our treks include trained guides and safety kits.",
        "Stay hydrated, watch your step, and follow your guide's instructions."
    ],
    "food": [
        "You’ll be served delicious local food on most treks!",
        "Have dietary preferences? Let us know in advance.",
        "We ensure hygienic and energizing meals during the trek."
    ],
    "cost": [
        "Prices vary by destination and duration. Want me to list options?",
        "We have budget and premium packages – what’s your preference?",
        "Group bookings get discounts. Planning with friends?"
    ],
    "guide": [
        "All our treks come with experienced local guides.",
        "You’ll be in good hands with our certified trekking leaders.",
        "Guides are trained in first aid and local knowledge."
    ],
    "equipment": [
        "Basic gear is provided, but you can bring your own if preferred.",
        "Need a tent, sleeping bag, or trekking pole? We’ve got rentals too.",
        "Sturdy shoes and a good backpack are must-haves!"
    ],
    "experience": [
        "No prior trekking experience? No problem – we have beginner-friendly treks.",
        "We cater to all levels, from first-timers to seasoned hikers.",
        "Let me know your fitness level and I’ll suggest the right trek."
    ]
}


def get_response(msg):
    msg = msg.lower()
    for key in responses:
        if key in msg:
            return random.choice(responses[key])
    return "I'm not sure how to respond to that. Could you rephrase?"

@app.route("/")
def home():
    return render_template("index.html")

@app.route("/get", methods=["GET"])
def chatbot_response():
    user_msg = request.args.get("msg")
    return jsonify({"response": get_response(user_msg)})

if __name__ == "__main__":
    app.run(debug=True)
