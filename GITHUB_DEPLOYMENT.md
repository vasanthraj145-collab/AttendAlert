# 🐙 GitHub Deployment & Version Control Guide

This guide details the step-by-step instructions for publishing **AttendAlert** to GitHub and managing version control.

---

## 🛠️ Step 1: Install Git (If not already installed)

Check if Git is installed on your local system:
```bash
git --version
```

If Git is not installed, download it from [https://git-scm.com](https://git-scm.com).

---

## 📦 Step 2: Initialize Git Repository Locally

Open PowerShell or Command Prompt in the `C:\xampp\htdocs\AttendAlert` folder and execute:

```bash
# 1. Navigate to project folder
cd C:\xampp\htdocs\AttendAlert

# 2. Initialize new Git repository
git init

# 3. Add all production files to staging
git add .

# 4. Create your initial commit
git commit -m "feat: Initial release of AttendAlert Enterprise Full-Stack Web App"
```

---

## 🌐 Step 3: Create GitHub Repository & Push Code

1. Go to [https://github.com/new](https://github.com/new).
2. Set Repository Name: `AttendAlert`.
3. Set Visibility: **Public** or **Private**.
4. Leave "Add a README file" unchecked (since we already created a production `README.md`).
5. Click **Create repository**.
6. Copy the repository URL (e.g., `https://github.com/your-username/AttendAlert.git`).
7. Run the following commands in your terminal:

```bash
# Link local repository to GitHub
git remote add origin https://github.com/YOUR_USERNAME/AttendAlert.git

# Set main branch
git branch -M main

# Push project code to GitHub
git push -u origin main
```

---

## 🚀 Step 4: Live Cloud Hosting Options (Post-GitHub)

Once your code is pushed to GitHub, you can host it live using:

| Provider | Description | Best For |
|---|---|---|
| **Render / Railway** | Full PHP + MySQL Cloud Hosting | Complete PHP/MySQL Backend |
| **000webhost / InfinityFree** | Free PHP & MySQL Web Hosting | Quick Live Demo |
| **Vercel / Netlify** | Static Frontend Deployment | Client-side Showcase |

---

## ✅ SDLC Automated Test Run Before Commit

Before pushing any future updates to GitHub, run the test runner to ensure zero regressions:

```bash
php test_app.php
```
