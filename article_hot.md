# Logic xác định bài viết hot
Hot Score = ViewScore + CommentScore + ShareScore + FreshScore
Yếu tố	Trọng số
View	1
Comment	5
Share	8
Fresh (bài mới)	20

# Tracking view realtime

# Queue job tăng view

# Cron job flush Redis → DB

# Job tính Hot Score

# kiến trúc
User view
   ↓
CDN
   ↓
Nginx
   ↓
Static HTML

Track view
   ↓
Kafka / Redis
   ↓
Analytics service
   ↓
Hot score engine
   ↓
Redis ranking