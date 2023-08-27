# 🤖 ChatGPT-Wrapper

ChatGPT-Wrapper is a sleek PHP interface for integrating with OpenAI's ChatGPT. 
With an easy configuration setup and both web and CLI modes, interfacing with ChatGPT has never been easier! 

## 🌟 Features

- 🌐 **Web Interface:** Start a local web server and chat directly through your browser.
- 💻 **CLI Mode:** Quick and efficient CLI tool for those who love the terminal.
- 🛠 **Configurable:** Separate configuration files for rules and API keys.

## 🚀 Getting Started

1. **Clone the Repository**

   ```bash
   git clone https://github.com/TimAnthonyAlexander/chatgpt-wrapper.git
   ```

2. **Navigate to the directory**

   ```bash
   cd chatgpt-wrapper
   ```

3. **Install Dependencies**

   ```bash
   composer install
   ```

4. **Configuration**

   - Add your OpenAI API key to `config/openai.txt`.
   - Modify `config/general.txt` for custom rules and behavior.

### Web Interface

Start the web server:

```bash
composer start-web
```

Once started, open your favorite browser and go to:

```
127.0.0.1:8864
```

### CLI Mode

For those who love the terminal:

```bash
composer start-cli
```

## 🔒 Security

Always keep your API keys secret! Never commit `config/openai.txt` to public repositories.

## 📝 Contribution

Got improvements? Open a PR! I would love additional features and improvements to the code.

## 📖 License

This project is under the XYZ license.
