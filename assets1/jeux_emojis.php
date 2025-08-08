<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu d'Emojis Tireur</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/18.2.0/umd/react.production.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react-dom/18.2.0/umd/react-dom.production.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/7.22.5/babel.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { margin: 0; }
        .cursor-crosshair { cursor: crosshair; }
    </style>
</head>
<body>
    <div id="root"></div>
    
    <script type="text/babel">
        const { useState, useEffect, useCallback } = React;

        const EmojiClickerGame = () => {
          const [score, setScore] = useState(0);
          const [timeLeft, setTimeLeft] = useState(30);
          const [gameStarted, setGameStarted] = useState(false);
          const [gameOver, setGameOver] = useState(false);
          const [emojis, setEmojis] = useState([]);
          const [highScore, setHighScore] = useState(0);
          const [mousePos, setMousePos] = useState({ x: 0, y: 0 });
          const [shots, setShots] = useState([]);
          const [ammo, setAmmo] = useState(50);

          const emojiList = ['😀', '😂', '🤣', '😍', '🥳', '🤪', '😎', '🤖', '👻', '🦄', '🌟', '🎉', '🍕', '🎮', '🚀'];

          const createEmoji = useCallback(() => {
            const emoji = emojiList[Math.floor(Math.random() * emojiList.length)];
            const id = Math.random();
            const x = Math.random() * 80;
            const y = Math.random() * 80;
            const speed = 0.5 + Math.random() * 2;
            const direction = Math.random() * 360;

            return {
              id,
              emoji,
              x,
              y,
              speed,
              direction,
              dx: Math.cos(direction) * speed,
              dy: Math.sin(direction) * speed
            };
          }, []);

          const startGame = () => {
            setScore(0);
            setTimeLeft(30);
            setGameStarted(true);
            setGameOver(false);
            setAmmo(50);
            setShots([]);
            setEmojis([createEmoji(), createEmoji(), createEmoji()]);
          };

          const handleMouseMove = (e) => {
            const rect = e.currentTarget.getBoundingClientRect();
            setMousePos({
              x: e.clientX - rect.left,
              y: e.clientY - rect.top
            });
          };

          const shoot = (e) => {
            if (!gameStarted || gameOver || ammo <= 0) return;
            
            e.preventDefault();
            const rect = e.currentTarget.getBoundingClientRect();
            const clickX = e.clientX - rect.left;
            const clickY = e.clientY - rect.top;
            
            setAmmo(prev => prev - 1);
            
            const shotId = Math.random();
            setShots(prev => [...prev, { id: shotId, x: clickX, y: clickY }]);
            
            setTimeout(() => {
              setShots(prev => prev.filter(shot => shot.id !== shotId));
            }, 300);
            
            const hitEmoji = emojis.find(emoji => {
              const emojiRect = {
                x: (emoji.x / 100) * rect.width,
                y: (emoji.y / 100) * rect.height,
                size: 40
              };
              
              const distance = Math.sqrt(
                Math.pow(clickX - emojiRect.x, 2) + Math.pow(clickY - emojiRect.y, 2)
              );
              
              return distance < emojiRect.size;
            });
            
            if (hitEmoji) {
              setScore(prev => prev + 15);
              setEmojis(prev => prev.filter(e => e.id !== hitEmoji.id));
              setAmmo(prev => prev + 2);
              setTimeout(() => {
                setEmojis(prev => [...prev, createEmoji()]);
              }, 200);
            }
          };

          useEffect(() => {
            if (!gameStarted || gameOver) return;

            const timer = setInterval(() => {
              setTimeLeft(prev => {
                if (prev <= 1) {
                  setGameStarted(false);
                  setGameOver(true);
                  if (score > highScore) {
                    setHighScore(score);
                  }
                  return 0;
                }
                return prev - 1;
              });
            }, 1000);

            return () => clearInterval(timer);
          }, [gameStarted, gameOver, score, highScore]);

          useEffect(() => {
            if (!gameStarted || gameOver) return;

            const moveEmojis = setInterval(() => {
              setEmojis(prev => prev.map(emoji => {
                let newX = emoji.x + emoji.dx;
                let newY = emoji.y + emoji.dy;
                let newDx = emoji.dx;
                let newDy = emoji.dy;

                if (newX <= 0 || newX >= 90) {
                  newDx = -newDx;
                  newX = Math.max(0, Math.min(90, newX));
                }
                if (newY <= 0 || newY >= 85) {
                  newDy = -newDy;
                  newY = Math.max(0, Math.min(85, newY));
                }

                return {
                  ...emoji,
                  x: newX,
                  y: newY,
                  dx: newDx,
                  dy: newDy
                };
              }));
            }, 50);

            return () => clearInterval(moveEmojis);
          }, [gameStarted, gameOver]);

          return React.createElement('div', {
            className: "min-h-screen bg-gradient-to-br from-purple-400 via-pink-500 to-red-500 p-4"
          }, React.createElement('div', {
            className: "max-w-4xl mx-auto"
          }, [
            React.createElement('h1', {
              key: 'title',
              className: "text-4xl font-bold text-white text-center mb-4 drop-shadow-lg"
            }, "🎮 Attrape les Emojis! 🎮"),
            
            React.createElement('div', {
              key: 'stats',
              className: "bg-white/20 backdrop-blur-sm rounded-xl p-6 mb-4"
            }, React.createElement('div', {
              className: "flex justify-between items-center text-white text-xl font-bold"
            }, [
              React.createElement('div', { key: 'score' }, `Score: ${score}`),
              React.createElement('div', { key: 'time' }, `Temps: ${timeLeft}s`),
              React.createElement('div', { key: 'ammo' }, `🔫 Munitions: ${ammo}`),
              React.createElement('div', { key: 'record' }, `Record: ${highScore}`)
            ])),

            !gameStarted && !gameOver && React.createElement('div', {
              key: 'start',
              className: "text-center bg-white/20 backdrop-blur-sm rounded-xl p-8"
            }, [
              React.createElement('h2', {
                key: 'subtitle',
                className: "text-2xl text-white mb-4"
              }, "🎯 Mode Tireur !"),
              React.createElement('p', {
                key: 'instructions',
                className: "text-white mb-6"
              }, [
                "Utilise ton viseur pour tirer sur les emojis qui bougent !",
                React.createElement('br', { key: 'br1' }),
                "🔫 Tire avec le clic gauche - chaque tir réussi te donne des munitions bonus !",
                React.createElement('br', { key: 'br2' }),
                "Tu as 30 secondes et 50 munitions pour faire le meilleur score !"
              ]),
              React.createElement('button', {
                key: 'startBtn',
                onClick: startGame,
                className: "bg-yellow-400 hover:bg-yellow-300 text-black font-bold py-3 px-8 rounded-full text-xl transform hover:scale-105 transition-all duration-200 shadow-lg"
              }, "🚀 Commencer le jeu !")
            ]),

            gameOver && React.createElement('div', {
              key: 'gameover',
              className: "text-center bg-white/20 backdrop-blur-sm rounded-xl p-8"
            }, [
              React.createElement('h2', {
                key: 'gameoverTitle',
                className: "text-3xl text-white mb-4"
              }, "🎉 Jeu terminé !"),
              React.createElement('p', {
                key: 'finalScore',
                className: "text-white text-xl mb-4"
              }, [
                "Score final: ",
                React.createElement('span', {
                  key: 'scoreSpan',
                  className: "font-bold text-yellow-300"
                }, score),
                " points"
              ]),
              score === highScore && score > 0 && React.createElement('p', {
                key: 'newRecord',
                className: "text-yellow-300 text-lg mb-4"
              }, "🏆 Nouveau record !"),
              React.createElement('button', {
                key: 'playAgainBtn',
                onClick: startGame,
                className: "bg-green-400 hover:bg-green-300 text-black font-bold py-3 px-8 rounded-full text-xl transform hover:scale-105 transition-all duration-200 shadow-lg"
              }, "🔄 Rejouer")
            ]),

            gameStarted && !gameOver && React.createElement('div', {
              key: 'gameArea',
              className: "relative bg-white/10 backdrop-blur-sm rounded-xl overflow-hidden cursor-crosshair",
              style: { height: '500px' },
              onMouseMove: handleMouseMove,
              onClick: shoot
            }, [
              React.createElement('div', {
                key: 'crosshair',
                className: "absolute pointer-events-none z-20",
                style: {
                  left: mousePos.x,
                  top: mousePos.y,
                  transform: 'translate(-50%, -50%)'
                }
              }, React.createElement('div', {
                className: "relative"
              }, [
                React.createElement('div', {
                  key: 'circle',
                  className: "w-8 h-8 border-2 border-red-400 rounded-full opacity-80"
                }),
                React.createElement('div', {
                  key: 'vertical',
                  className: "absolute top-1/2 left-1/2 w-0.5 h-4 bg-red-400 transform -translate-x-1/2 -translate-y-1/2"
                }),
                React.createElement('div', {
                  key: 'horizontal',
                  className: "absolute top-1/2 left-1/2 w-4 h-0.5 bg-red-400 transform -translate-x-1/2 -translate-y-1/2"
                })
              ])),

              shots.map(shot => React.createElement('div', {
                key: shot.id,
                className: "absolute pointer-events-none z-10 animate-ping",
                style: {
                  left: shot.x,
                  top: shot.y,
                  transform: 'translate(-50%, -50%)'
                }
              }, React.createElement('div', {
                className: "w-6 h-6 bg-yellow-400 rounded-full opacity-75"
              }))),

              emojis.map(emoji => React.createElement('div', {
                key: emoji.id,
                className: "absolute text-4xl select-none pointer-events-none transform transition-all duration-100 hover:scale-110",
                style: {
                  left: `${emoji.x}%`,
                  top: `${emoji.y}%`,
                  transform: 'translate(-50%, -50%)',
                  filter: 'drop-shadow(2px 2px 4px rgba(0,0,0,0.3))'
                }
              }, emoji.emoji)),

              emojis.length === 0 && React.createElement('div', {
                key: 'preparing',
                className: "absolute inset-0 flex items-center justify-center text-white text-xl"
              }, "Prépare ton arme... 🎯"),

              ammo === 0 && React.createElement('div', {
                key: 'noAmmo',
                className: "absolute inset-0 flex items-center justify-center bg-black/50 text-white text-2xl font-bold"
              }, "🚫 Plus de munitions !")
            ]),

            React.createElement('div', {
              key: 'tips',
              className: "text-center mt-6 text-white/80"
            }, [
              React.createElement('p', { key: 'tip1' }, "🎯 Astuce: Vise bien ! Chaque tir réussi te donne des munitions bonus !"),
              React.createElement('p', { key: 'tip2' }, "🔫 Les emojis bougent et rebondissent - anticipe leurs mouvements !")
            ])
          ]));
        };

        ReactDOM.render(React.createElement(EmojiClickerGame), document.getElementById('root'));
    </script>
</body>
</html>