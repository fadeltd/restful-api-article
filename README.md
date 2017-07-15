Buatlah restful API dengan spesifikasi berikut:

- Push to github
- write installation & run instruction in file readme
- write API documentation in wiki

- [User login]
  - menggunakan email & password
  - success response must be 200 http code & return JWT token
  - failed responsemust be 401 http code

- [user create article] (POST)
  - endpoint must be protected by user JWT token
  - title & content is required, min 30 chars (title)
  - success response must be 200 http code & return article data
  - failed validation response must be 422 http code

- [user get article list] (GET)
  - endpoint must be protected bu user JWT token
  - user is able to paginate data by 10 article / page
  - only user's article is displayed
  - success response must be 200 http code & return paginated article data

- [user edit article] (PUT)
  - endpoint must be protected by user JWT token
  - only article's owner is authorized to do edit article
  - title & content is required, min 30 chars (title)
  - success response must be 200 http code & return article data
  - failed validation response must be 422 http code

- [user delete article] (DELETE)
  - endpoint must be protected by user JWT token
  - only article's owner is authorized to do delete article
  - article id must be exist
  - success response must be 200 http code & return article data
  - failed validation response must be 422 http code


Technical Knowledge & Experience

> Jelaskan bagaimana anda menggunakan GIT untuk proses development sampai deployment production
Proses Development
- Untuk menggunakan Git, diperlukan instalasi Git pada environment development,
- Pada windows, Git di install dengan mendownload Git pada https://git-scm.com/downloads
- Setelah instalasi git selesai, buka command prompt
- Ganti directory menjadi directory environment application yang sedang dikerjakan
  - cd C:/xampp/htdocs/rest-server
- Untuk memulai Git masukkan command
  - git init
- Untuk menambahkan semua code yang sudah ada (stage change)
  - git add .
- Untuk melakukan commit terhadap code yang sudah diganti gunakan command dengan -m adalah message pada commit
  - git commit -m "message"
- Jika menggunakan Git server sebagai remote (seperti github)
  - git remote add origin https://github.com/fadeltd/rest-api-article.git
- Untuk menambahkan code yang sudah ada pada git lakukan push dengan command
  - git push origin master
Proses Deployment
- Gunakan git hook
https://gist.github.com/noelboss/3fe13927025b89757f8fb12e9066f2fa

> Apa yang anda ketahui dengan code refactoring? Jika tahu, bagaimana anda melakukannya?

Refactoring adalah proses menstrukrisasi code yang sudah ada tanpa mengubah sedikitpun fungsionalitas dari sistem. 
Artinya, pada proses refactoring dilakukan modifikasi program untuk memperbaiki struktur, 
mengurangi kompleksitas, atau untuk membuatnya lebih mudah dimengerti.