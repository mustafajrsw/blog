# Laravel Web Routes Reference

## 🏠 General
| Route | Path | Method | Name |
|--------|------|---------|------|
| `/` | GET | — | `home` |

---

## 🔐 Authentication (AuthController)

### Registration & Login
| Path | Method | Name |
|------|---------|------|
| `auth/register` | GET | `auth.register.form` |
| `auth/register` | POST | `auth.register` |
| `auth/login` | GET | `auth.login.form` |
| `auth/login` | POST | `auth.login` |

### Password
| Path | Method | Name |
|------|---------|------|
| `auth/password/forgot` | GET | `auth.password.forgot.form` |
| `auth/password/forgot` | POST | `auth.password.forgot` |
| `auth/password/reset/{token}` | GET | `auth.password.reset.form` |
| `auth/password/reset` | POST | `auth.password.reset` |
| `auth/password/change` | GET | `auth.password.change.form` |
| `auth/password/change` | PUT | `auth.password.change` |

### Email
| Path | Method | Name |
|------|---------|------|
| `auth/email/verify/{token}` | GET | `auth.email.verify` |
| `auth/email/re-verify` | GET | `auth.email.reverify` |

### Sessions
| Path | Method | Name |
|------|---------|------|
| `auth/sessions/active` | GET | `auth.sessions.active` |
| `auth/sessions/current` | GET | `auth.sessions.current` |
| `auth/sessions/others` | GET | `auth.sessions.others` |
| `auth/sessions/{id}` | GET | `auth.sessions.show` |

### Logout
| Path | Method | Name |
|------|---------|------|
| `auth/logout/all` | POST | `auth.logout.all` |
| `auth/logout/current` | POST | `auth.logout.current` |
| `auth/logout/others` | POST | `auth.logout.others` |
| `auth/logout/{id}` | GET | `auth.logout.session` |

### Profile (Logged-in User)
| Path | Method | Name |
|------|---------|------|
| `auth/profile` | GET | `auth.profile.show` |
| `auth/profile/edit` | GET | `auth.profile.edit` |
| `auth/profile` | PUT | `auth.profile.update` |
| `auth/profile/change-photo` | POST | `auth.profile.change_photo` |

---

## 📝 Posts (PostController)
| Path | Method | Name |
|------|---------|------|
| `posts/deleted` | GET | `posts.deleted` |
| `posts/restore/{id}` | GET | `posts.restore` |
| `posts/force-delete/{id}` | DELETE | `posts.force_delete` |
| `posts` | GET | `posts.index` |
| `posts/create` | GET | `posts.create` |
| `posts` | POST | `posts.store` |
| `posts/{post}` | GET | `posts.show` |
| `posts/{post}/edit` | GET | `posts.edit` |
| `posts/{post}` | PUT/PATCH | `posts.update` |
| `posts/{post}` | DELETE | `posts.destroy` |

---

## 💬 Comments (CommentController)
| Path | Method | Name |
|------|---------|------|
| `comments/deleted` | GET | `comments.deleted` |
| `comments/restore/{id}` | GET | `comments.restore` |
| `comments/force-delete/{id}` | DELETE | `comments.force_delete` |
| ... | ... | `comments.*` |

---

## 💬 Replies (ReplyController)
| Path | Method | Name |
|------|---------|------|
| `replies/deleted` | GET | `replies.deleted` |
| `replies/restore/{id}` | GET | `replies.restore` |
| `replies/force-delete/{id}` | DELETE | `replies.force_delete` |
| ... | ... | `replies.*` |

---

## 👍 Reactions (ReactionController)
| Path | Method | Name |
|------|---------|------|
| `reactions/deleted` | GET | `reactions.deleted` |
| `reactions/restore/{id}` | GET | `reactions.restore` |
| `reactions/force-delete/{id}` | DELETE | `reactions.force_delete` |
| ... | ... | `reactions.*` |

---

## 📊 Post Statuses (PostStatusController)
| Path | Method | Name |
|------|---------|------|
| `post-statuses/deleted` | GET | `post-statuses.deleted` |
| `post-statuses/restore/{id}` | GET | `post-statuses.restore` |
| `post-statuses/force-delete/{id}` | DELETE | `post-statuses.force_delete` |
| ... | ... | `post-statuses.*` |

---

## 🎭 Reaction Types (ReactionTypeController)
| Path | Method | Name |
|------|---------|------|
| `reaction-types/deleted` | GET | `reaction-types.deleted` |
| `reaction-types/restore/{id}` | GET | `reaction-types.restore` |
| `reaction-types/force-delete/{id}` | DELETE | `reaction-types.force_delete` |
| ... | ... | `reaction-types.*` |

---

## 👤 Users (UserController)
| Path | Method | Name |
|------|---------|------|
| `users/deleted` | GET | `users.deleted` |
| `users/restore/{id}` | GET | `users.restore` |
| `users/force-delete/{id}` | DELETE | `users.force_delete` |
| `users/activate/{id}` | GET | `users.activate` |
| `users/deactivate/{id}` | GET | `users.deactivate` |
| ... | ... | `users.*` |

---

## 🧑‍💼 Profiles (ProfileController)
| Path | Method | Name |
|------|---------|------|
| `profiles/deleted` | GET | `profiles.deleted` |
| `profiles/restore/{id}` | GET | `profiles.restore` |
| `profiles/force-delete/{id}` | DELETE | `profiles.force_delete` |
| ... | ... | `profiles.*` |

---

### ✅ Usage Examples
```blade
<a href="{{ route('auth.login.form') }}">Login</a>
<a href="{{ route('auth.register.form') }}">Register</a>
<a href="{{ route('posts.index') }}">All Posts</a>
<a href="{{ route('users.activate', $user->id) }}">Activate User</a>
<a href="{{ route('profiles.edit', $profile->id) }}">Edit Profile</a>
```
