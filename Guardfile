# A sample Guardfile
# More info at https://github.com/guard/guard#readme

guard 'phpunit2', :cli => '--colors', tests_path: 'tests' do

  watch(%r{^.+Test\.php$})

  watch(%r{lib/RubyRainbows/Cache/(.+)\.php}) { |m| "tests/RubyRainbows/Cache/#{m[1]}Test.php" }
  watch(%r{lib/RubyRainbows/Cache/Providers/(.+)\/Objects/(.+)\.php}) { |m| "tests/RubyRainbows/Cache/Providers/#{m[1]}/Objects/#{m[2]}Test.php" }
  watch(%r{lib/RubyRainbows/Cache/Providers/(.+)\/(.+)\/(.+)\.php}) { |m| "tests/RubyRainbows/Cache/Providers/#{m[1]}/#{m[2]}/#{m[3]}Test.php" }
  watch(%r{lib/RubyRainbows/Cache/Providers/(.+)\/(.+)\.php}) { |m| "tests/RubyRainbows/Cache/Providers/#{m[1]}/#{m[2]}Test.php" }
end
