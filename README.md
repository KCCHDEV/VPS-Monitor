# VPS System Monitor 🖥️

A modern, real-time VPS monitoring dashboard with a beautiful dark theme and responsive design.

## ✨ Features

### 🎨 Modern UI/UX
- **Dark Theme**: Professional dark color scheme with gradient accents
- **Responsive Design**: Mobile-first approach with perfect scaling across devices
- **Smooth Animations**: Fade-in effects, hover transitions, and loading states
- **Interactive Elements**: Animated progress bars and gauge charts
- **Modern Typography**: Clean Inter font family for better readability

### 📊 Real-time Monitoring
- **Memory Usage**: RAM consumption with cache information
- **Storage Usage**: Disk space utilization with visual progress bars
- **CPU Performance**: Real-time CPU load with detailed core information
- **Network Activity**: Bandwidth usage with packet statistics
- **System Information**: Uptime, OS details, and load averages

### 📈 Advanced Charts
- **Live Performance Charts**: Real-time line charts with time-series data
- **Interactive Gauges**: Beautiful circular gauges showing system load
- **Range Selection**: 1M, 5M, 10M, 15M time ranges for historical data
- **High Usage Alerts**: Visual indicators for critical resource usage

### 🚀 Technical Improvements
- **Error Handling**: Robust error handling with user-friendly messages
- **Loading States**: Visual feedback during data fetching
- **API Improvements**: Enhanced PHP backend with better error handling
- **Performance**: Optimized JavaScript with reduced redundancy
- **Accessibility**: Better contrast ratios and semantic HTML

## 🛠️ Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd vps-monitor
   ```

2. **Start the PHP server**
   ```bash
   php -S localhost:8000
   ```

3. **Open in browser**
   ```
   http://localhost:8000
   ```

## 📱 Browser Compatibility

- ✅ Chrome 80+
- ✅ Firefox 75+
- ✅ Safari 13+
- ✅ Edge 80+

## 🎯 Key Improvements Made

### UI/UX Enhancements
- Modern dark theme with CSS custom properties
- Gradient backgrounds and professional color palette
- Hover effects and smooth transitions
- Mobile-responsive grid layout
- Loading spinners and error states
- Visual progress bars for resource usage

### Performance Optimizations
- Reduced API calls with loading state management
- Optimized chart rendering with better animations
- Improved data formatting and display
- Better error handling and fallbacks

### Code Quality
- Modular JavaScript functions
- Comprehensive error handling in PHP
- Better separation of concerns
- Improved code documentation

## 🔧 Configuration

The monitoring refreshes every 3 seconds by default. You can modify the refresh rate in `script.js`:

```javascript
var refresh_time = 3; // seconds
```

## 📊 System Requirements

- PHP 7.4+ with CLI support
- Linux system with /proc filesystem
- Modern web browser with JavaScript enabled

## 🚀 Future Enhancements

- [ ] Historical data storage
- [ ] Email/SMS alerts for critical thresholds
- [ ] Multi-server monitoring
- [ ] Custom dashboard widgets
- [ ] Export functionality for reports

## 📄 License

This project is open source and available under the MIT License.

---

Made with ❤️ for better VPS monitoring